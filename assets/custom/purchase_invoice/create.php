<?php
    //ini_set('display_errors', 1);
    include ("../connect.php");
    include ("../php_replace_improper.php");
    include ("../fy_access.php");

    $request = file_get_contents('php://input');
    $input = json_decode($request);
    $array = json_decode($request, true);

    if (!$input || !is_array($array)) {
        echo json_encode(array("success" => false, "messages" => "Invalid request"));
        exit;
    }

    session_start();
    function createWhatsAppQueueEntryForPurchase($supplier, $order_no, $order_date, $mobile, $tot_amount,$series, $log_user) {
        global $db;
        
        // Define template parameters
        $template_name = 'purchase_order_template'; // WhatsApp template name for purchase orders
        $language_code = 'en'; // Language code for WhatsApp template
        
        if($series=='PRIMARY')
        $link = "?page=purchase";
    else
    $link = "?page=secondary_purchase";
        // Prepare template content
        $template_data = [
            'name' => $template_name,
            'language' => ['code' => $language_code],
            'components' => [
                [
                    'type' => 'body',
                    'parameters' => [
                        // Header
                        ['type' => 'text', 'text' => $supplier], // Supplier name
                        ['type' => 'text', 'text' => $log_user], // Placeholder for created by user
                        ['type' => 'text', 'text' => number_format((float)$tot_amount,2)], // Purchase order total amount
                        ['type' => 'text', 'text' => $order_no], // Purchase order number
                        ['type' => 'text', 'text' => $order_date] // Purchase order date
                    ]
                ],
                [
                    'type' => 'button',
                    'sub_type' => 'url',
                    'index' => 0,
                    'parameters' => [
                        ['type' => 'text', 'text' => $link] // Link to the purchase order
                    ]
                ]
            ]
        ];
        
        // Queue data for wa_purchase_queue table
        $queue_data = [
            'group_id' => "purchase_" . $order_no, // Unique group ID based on purchase order number
            'callback_data' => $order_no . '_unique_identifier', // Callback identifier for tracking
            'recipient_type' => 'individual', // For individual messages
            'to' => "917003310926", // Recipient's phone number
            'type' => 'template', // Template type
            'file_url' => '', // No file attachment
            'content' => json_encode($template_data), // Template content in JSON format
            'status' => 0, // Status set to pending
            'response' => '', // Empty initially
            'msg_id' => '', // To be updated after sending
            'msg_status' => '', // To be updated after sending
            'timestamp' => date('Y-m-d H:i:s') // Current timestamp
        ];
        
        // Insert data into wa_purchase_queue
        $sql = "INSERT INTO tblwa_sales_queue (`group_id`, `callback_data`, `recipient_type`, `to`, `type`, `file_url`, `content`, `status`, `response`, `msg_id`, `msg_status`, `timestamp`) 
                VALUES ('{$queue_data['group_id']}', '{$queue_data['callback_data']}', '{$queue_data['recipient_type']}', '{$queue_data['to']}', '{$queue_data['type']}', '{$queue_data['file_url']}', '{$queue_data['content']}', '{$queue_data['status']}', '{$queue_data['response']}', '{$queue_data['msg_id']}', '{$queue_data['msg_status']}', '{$queue_data['timestamp']}')";
        
        $db->query($sql);
    }

    function getConsignmentQtyByType($db, $supplier, $product, $voucher_type){
        $safe_supplier = $db->real_escape_string($supplier);
        $safe_product = $db->real_escape_string($product);
        $sql = "SELECT items FROM materials_received WHERE supplier_name = '$safe_supplier' AND voucher_type = '$voucher_type' AND items LIKE '%$safe_product%'";
        $query = $db->query($sql);
        $total = 0.0;
        if($query){
            while($row = $query->fetch_assoc()){
                $items = json_decode($row['items'] ?? '', true);
                if (!is_array($items)) { $items = ['product'=>[]]; }
                if(!isset($items['product']) || !is_array($items['product'])){ continue; }
                $len = sizeof($items['product']);
                for($i=0;$i<$len;$i++){
                    if($items['product'][$i] === $product){
                        $total += isset($items['quantity'][$i]) ? (float)$items['quantity'][$i] : 0.0;
                    }
                }
            }
        }
        return $total;
    }

    function getSettledQty($db, $supplier, $product){
        $safe_supplier = $db->real_escape_string($supplier);
        $safe_product = $db->real_escape_string($product);
        $sql = "SELECT IFNULL(SUM(quantity),0) AS settled_qty FROM consignment_settlements WHERE supplier_name = '$safe_supplier' AND product_name = '$safe_product'";
        $query = $db->query($sql);
        if($query && $query->num_rows > 0){
            $row = $query->fetch_assoc();
            return (float)$row['settled_qty'];
        }
        return 0.0;
    }

    function syncConsignmentSettlements($db, $pi_id, $pi_no, $supplier, $invoice_date, $items){
        $safe_pi_id = (int)$pi_id;
        $safe_supplier = $db->real_escape_string($supplier);
        $safe_pi_no = $db->real_escape_string($pi_no);
        $safe_date = $db->real_escape_string($invoice_date);
        $db->query("DELETE FROM consignment_settlements WHERE purchase_invoice_id = '$safe_pi_id'");

        if(!isset($items['product']) || !is_array($items['product'])){ return; }
        $len = sizeof($items['product']);
        for($i=0;$i<$len;$i++){
            $product = isset($items['product'][$i]) ? $items['product'][$i] : '';
            $qty = isset($items['quantity'][$i]) ? (float)$items['quantity'][$i] : 0.0;
            if($product === '' || $qty <= 0){ continue; }

            $received = getConsignmentQtyByType($db, $supplier, $product, 'MRN');
            $returned = getConsignmentQtyByType($db, $supplier, $product, 'MRTN');
            $already_settled = getSettledQty($db, $supplier, $product);
            $available = $received - $returned - $already_settled;
            if($available <= 0){ continue; }
            $absorbed = ($qty <= $available) ? $qty : $available;
            if($absorbed <= 0){ continue; }

            $safe_product = $db->real_escape_string($product);
            $db->query("INSERT INTO consignment_settlements (`purchase_invoice_id`,`purchase_invoice_no`,`supplier_name`,`product_name`,`quantity`,`basis`,`settled_on`) VALUES ('$safe_pi_id','$safe_pi_no','$safe_supplier','$safe_product','$absorbed','FIFO_POOL','$safe_date')");
        }
    }

    $validator      = array("success"=>true, "messages"=>"There was some error saving the records","pi"=>"");

    $pi_id          = $input->edit_pi_id ?? '';
   

    $log_user       = $_SESSION['username'] ?? '';
    $log_date       = date('Y-m-d', strtotime("today"));

    $order_no           = $input->purchase_invoice_no ?? '';
    $series             = $input->series ?? '';
    if(empty($series))
    $series="PRIMARY";
    $mobile             = $input->mobile ?? '';

    $pi_date_raw = $input->purchase_invoice_date ?? '';
    $order_date     = ($pi_date_raw !== '') ? date('Y-m-d', strtotime($pi_date_raw)) : '';
    fy_assert_or_exit_json($order_date, "Purchase invoice date");
    $supplier       = replace_improper($input->pi_supplier ?? '');
    $po_no          = $input->pi_purchase_order ?? [];
    if (!is_array($po_no)) { $po_no = []; }
    $spi_no         = $input->purchase_invoice_pno ?? '';

    $pi_pf          = replace_improper_amount($input->pi_pf ?? '');
    $pi_pf_cgst     = replace_improper_amount($input->pi_pf_cgst ?? '');
    $pi_pf_sgst     = replace_improper_amount($input->pi_pf_sgst ?? '');
    $pi_pf_igst     = replace_improper_amount($input->pi_pf_igst ?? '');

    $pi_freight      = replace_improper_amount($input->pi_freight ?? '');
    $pi_freight_cgst = replace_improper_amount($input->pi_freight_cgst ?? '');
    $pi_freight_sgst = replace_improper_amount($input->pi_freight_sgst ?? '');
    $pi_freight_igst = replace_improper_amount($input->pi_freight_igst ?? '');

    $pi_tcs         = replace_improper_amount($input->pi_tcs ?? '');


    $sql_pull = "SELECT * FROM suppliers WHERE name = '$supplier'";
    $query_pull = $db->query($sql_pull);
    $row_pull = ($query_pull && ($tmp = $query_pull->fetch_assoc())) ? $tmp : [];
    $state = strtoupper((string)($row_pull['state'] ?? ''));

    $pi_pf          = str_replace(",","",$pi_pf);
    $pi_freight     = str_replace(",","",$pi_freight);
    $pi_tcs         = str_replace(",","",$pi_tcs);

    $tot_amount = replace_improper_amount($input->pi_total_final ?? '');
    $tot_amount = TrimTrailingZeroes(number_format((float)$tot_amount,2, '.', ''));

    $tax = array("cgst" => 0.00, "sgst" => 0.00, "igst" => 0.00); // Initialize as float


    $items=array('product'=>array(),'desc'=>array(),'long_desc'=>array(),'group'=>array(),'quantity'=>array(),'unit'=>array(),'price'=>array(),'discount'=>array(),'hsn'=>array(),'tax'=>array());

    $group=0;

    $address=array('address1'=>'','address2'=>'','address3'=>'');
    $address['address1']=replace_improper($input->shipping_add_1 ?? '');
    $address['address2']=replace_improper($input->shipping_add_2 ?? '');
    $address['address3']=replace_improper($input->shipping_add_3 ?? '');

    $address=json_encode($address);

    $secondary_total = 0;
    $purchase_invoice_items = $array['purchase_invoice'] ?? [];
    if (!is_array($purchase_invoice_items)) { $purchase_invoice_items = []; }
foreach ($purchase_invoice_items as $item) {
        if ($item['pi_product_name'] != '' && $item['pi_qty'] != '') {
            // Add items to the array
            $items['product'][] = replace_improper($item['pi_product_name']);
            $items['desc'][] = replace_improper($item['pi_product_description']);
            $items['long_desc'][] = replace_improper_textarea($item['pi_product_add_description']);
            $items['quantity'][] = replace_improper($item['pi_qty']);
            $items['unit'][] = replace_improper($item['pi_unit']);
            $items['price'][] = replace_improper($item['pi_rate']);
            $items['tax'][] = replace_improper($item['pi_tax']);
    
            // Handle tax values
            $cgst = isset($item['pi_cgst']) ? (float)$item['pi_cgst'] : 0.0;
            $sgst = isset($item['pi_sgst']) ? (float)$item['pi_sgst'] : 0.0;
            $igst = isset($item['pi_igst']) ? (float)$item['pi_igst'] : 0.0;
    
            if ($state == 'WEST BENGAL') {
                $items['cgst'][] = $cgst;
                $items['sgst'][] = $sgst;
                $tax['cgst'] += $cgst;
                $tax['sgst'] += $sgst;
            } else {
                $items['igst'][] = $igst;
                $tax['igst'] += 1;
            }
         
            
            $secondary_total = $secondary_total + $item['pi_gross_pr'];

     
        }
    }
        

            $secondary_total = $secondary_total + $item['pi_gross_pr'];

     
    $item=json_encode($items);

    $status=0;
    $addons = array('freight'=>array('value'=>$pi_freight,'cgst'=>'','sgst'=>'','igst'=>''),'pf'=>array('value'=>$pi_pf,'cgst'=>'','sgst'=>'','igst'=>''),'roundoff'=>'','tcs'=>$pi_tcs);

    if ($state == 'WEST BENGAL') {
        $addons['freight']['cgst'] = isset($pi_freight_cgst) ? (float)$pi_freight_cgst : 0.0;
        $addons['freight']['sgst'] = isset($pi_freight_sgst) ? (float)$pi_freight_sgst : 0.0;
        $tax['cgst'] += $addons['freight']['cgst'];
        $tax['sgst'] += $addons['freight']['sgst'];
    
        $addons['pf']['cgst'] = isset($pi_pf_cgst) ? (float)$pi_pf_cgst : 0.0;
        $addons['pf']['sgst'] = isset($pi_pf_sgst) ? (float)$pi_pf_sgst : 0.0;
        $tax['cgst'] += $addons['pf']['cgst'];
        $tax['sgst'] += $addons['pf']['sgst'];
    } else {
        $addons['freight']['igst'] = isset($pi_freight_igst) ? (float)$pi_freight_igst : 0.0;
        $tax['igst'] += $addons['freight']['igst'];
    
        $addons['pf']['igst'] = isset($pi_pf_igst) ? (float)$pi_pf_igst : 0.0;
        $tax['igst'] += $addons['pf']['igst'];
    }
    

    $tax['cgst'] = number_format((float)$tax['cgst'], 2, '.', '');
    $tax['sgst'] = number_format((float)$tax['sgst'], 2, '.', '');
    $tax['igst'] = number_format((float)$tax['igst'], 2, '.', '');
    

    $addons['roundoff'] = $input->pi_round ?? '';

    $addon      = json_encode($addons);
    $tax_json   = json_encode($tax);

    $purchase_order=array();
    $e_len = sizeof($po_no);
    for($i=0;$i<$e_len;$i++){
        $purchase_order[] = $po_no[$i];
    }
    $purchase_o=json_encode($purchase_order);

    // if($series == 'SECONDARY')
    // {
    //     $tot_amount = $secondary_total;
    // }

    if($pi_id == '')
    {

        //die("inide here ".$pi_id);
        $sql = "INSERT INTO purchase_invoice (`supplier_name`,`mobile`,`series`,`pi_no`,`spi_no`,`pi_date`,`po_no`,`shipping`,`items`,`addons`,`total`,`tax`,`status`,`log_user`,`log_date`) VALUES ('$supplier','$mobile','$series','$order_no','$spi_no', '$order_date','$purchase_o','$address','$item','$addon','$tot_amount','$tax_json','$status','$log_user','$log_date')";
        $query = $db->query($sql);
      

        if($query===true)
        {
            $pi_insert_id = $db->insert_id;
            syncConsignmentSettlements($db, $pi_insert_id, $order_no, $supplier, $order_date, $items);
            

            $validator['success'] = true;
            $validator['messages'] = "Successfully Added";
            $validator['pi'] = $order_no;
        }
        else
        {
            $validator['success'] = false;
            $validator['messages'] = "There was some error saving the records";

        }

        if($series == 'SECONDARY')
        {
            $sql_counter = "SELECT * FROM counter WHERE `key` = 'secondary_purchase'";
            $query_counter = $db->query($sql_counter);
            if ($query_counter && $query_counter->num_rows > 0) {
                $row_counter = $query_counter->fetch_assoc();
                $row_counter_arr = json_decode($row_counter['value'] ?? '', true);
                if (is_array($row_counter_arr) && isset($row_counter_arr['prefix'][0], $row_counter_arr['number'][0], $row_counter_arr['postfix'][0])) {
                    $order_no = $row_counter_arr['prefix'][0].$row_counter_arr['number'][0].$row_counter_arr['postfix'][0];
                    $row_counter_arr['number'][0] = $row_counter_arr['number'][0] + 1;

                    $counter_array = json_encode($row_counter_arr);
                    $sql_counter = "UPDATE counter SET `value` = '$counter_array' WHERE `key` = 'secondary_purchase'";
                    $query_counter = $db->query($sql_counter);
                }
            }
        }
        else if($series == 'PRIMARY')
        {
            $sql_counter = "SELECT * FROM counter WHERE `key` = 'purchase_invoice'";
            $query_counter = $db->query($sql_counter);
            if ($query_counter && $query_counter->num_rows > 0) {
                $row_counter = $query_counter->fetch_assoc();
                $row_counter_arr = json_decode($row_counter['value'] ?? '', true);
                if (is_array($row_counter_arr) && isset($row_counter_arr['prefix'][0], $row_counter_arr['number'][0], $row_counter_arr['postfix'][0])) {
                    $order_no = $row_counter_arr['prefix'][0].$row_counter_arr['number'][0].$row_counter_arr['postfix'][0];
                    $row_counter_arr['number'][0] = $row_counter_arr['number'][0] + 1;

                    $counter_array = json_encode($row_counter_arr);
                    $sql_counter = "UPDATE counter SET `value` = '$counter_array' WHERE `key` = 'purchase_invoice'";
                    $query_counter = $db->query($sql_counter);
                }
            }
        }
        //createWhatsAppQueueEntryForPurchase($supplier, $order_no, $order_date, $mobile, $tot_amount,$series, $log_user);

    }
    else{
        //die("inide not here ".$pi_id);
        $sql = "UPDATE purchase_invoice SET `supplier_name` = '$supplier',`mobile`='$mobile',`series`='$series', `pi_no`='$order_no',`spi_no`='$spi_no',`pi_date`='$order_date', `po_no`='$purchase_o',`shipping`='$address',`items`='$item',`addons`='$addon', `total` = '$tot_amount', `tax` = '$tax_json',`log_user`='$log_user',`log_date`='$log_date' WHERE `id`='$pi_id'";
        $query = $db->query($sql);
        //die( $sql);

        if($query===true)
        {
            syncConsignmentSettlements($db, $pi_id, $order_no, $supplier, $order_date, $items);
            $validator['success'] = true;
            $validator['messages'] = "Successfully Updated";
            $validator['pi'] = $order_no;
        }
        else
        {
            $validator['success'] = false;
            $validator['messages'] = "There was some error saving the records";

        }
    }

    echo json_encode($validator);

    function TrimTrailingZeroes($nbr) {
        return strpos($nbr,'.')!==false ? rtrim(rtrim($nbr,'0'),'.') : $nbr;
    }
?>