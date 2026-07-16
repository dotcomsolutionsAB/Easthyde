<?php
    include ("../connect.php");
    include ("../php_replace_improper.php");
    include ("../fy_access.php");

    session_start();
    global $db;

    function TrimTrailingZeroes($nbr) {
        return strpos((string)$nbr, '.') !== false ? rtrim(rtrim((string)$nbr, '0'), '.') : (string)$nbr;
    }

    function createWhatsAppQueueEntry($client, $order_no, $invoice_date, $mobile, $tot_amount, $series, $log_user) {
        global $db;
        $template_name = 'shht_invoice';
        $language_code = 'en';

        if ($series == 'PRIMARY') {
            $link = "sales_print.php?id=$order_no&type=print";
        } else {
            $link = "sales_secondary_print.php?id=$order_no&type=print";
        }

        $template_data = [
            'name' => $template_name,
            'language' => ['code' => $language_code],
            'components' => [
                [
                    'type' => 'body',
                    'parameters' => [
                        ['type' => 'text', 'text' => (string)$client],
                        ['type' => 'text', 'text' => (string)$log_user],
                        ['type' => 'text', 'text' => (string)$tot_amount],
                        ['type' => 'text', 'text' => (string)$order_no],
                        ['type' => 'text', 'text' => (string)$invoice_date]
                    ]
                ],
                [
                    'type' => 'button',
                    'sub_type' => 'url',
                    'index' => 0,
                    'parameters' => [
                        ['type' => 'text', 'text' => (string)$link]
                    ]
                ]
            ]
        ];

        $queue_data = [
            'group_id' => "invoice_" . $order_no,
            'callback_data' => $order_no . '_unique_identifier',
            'recipient_type' => 'individual',
            'to' => '917003310926',
            'type' => 'template',
            'file_url' => '',
            'content' => $db->real_escape_string(json_encode($template_data)),
            'status' => 0,
            'response' => '',
            'msg_id' => '',
            'msg_status' => '',
            'timestamp' => date('Y-m-d H:i:s')
        ];

        $sql = "INSERT INTO tblwa_sales_queue (`group_id`, `callback_data`, `recipient_type`, `to`, `type`, `file_url`, `content`, `status`, `response`, `msg_id`, `msg_status`, `timestamp`) 
                VALUES ('{$queue_data['group_id']}', '{$queue_data['callback_data']}', '{$queue_data['recipient_type']}', '{$queue_data['to']}', '{$queue_data['type']}', '{$queue_data['file_url']}', '{$queue_data['content']}', '{$queue_data['status']}', '{$queue_data['response']}', '{$queue_data['msg_id']}', '{$queue_data['msg_status']}', '{$queue_data['timestamp']}')";
        $db->query($sql);
    }

    $id         = $_REQUEST['edit_si_id'] ?? '';
    $log_user   = $_SESSION['username'] ?? '';
    $log_date   = date('Y-m-d', strtotime("today"));
    $validator  = array("success"=>true, "messages"=>"There was some error saving the records","si"=>"");

    $array      = $_REQUEST['sales_invoice'] ?? [];
    if (!is_array($array)) { $array = []; }
    $l          = sizeof($array);

    $client         = replace_improper($_REQUEST['si_client'] ?? '');
    $order_no       = replace_improper($_REQUEST['sales_invoice_no'] ?? '');
    $si_date_raw    = $_REQUEST['sales_invoice_date'] ?? '';
    $invoice_date   = ($si_date_raw !== '') ? date('Y-m-d', strtotime($si_date_raw)) : '';
    fy_assert_or_exit_json($invoice_date, "Sales invoice date");
    $series         = $_REQUEST['si_series'] ?? '';
    $mobile         = $_REQUEST['mobile'] ?? '';

    $address                = array('name'=>'','address_1'=>'','address_2'=>'','city'=>'','pincode'=>'','country'=>'');
    $address['name']        = replace_improper_same($_REQUEST['shipping_name'] ?? '');
    $address['address_1']   = replace_improper_same($_REQUEST['shipping_add_1'] ?? '');
    $address['address_2']   = replace_improper_same($_REQUEST['shipping_add_2'] ?? '');
    $address['city']        = replace_improper_same($_REQUEST['shipping_city'] ?? '');
    $address['pincode']     = replace_improper_same($_REQUEST['shipping_pincode'] ?? '');
    $address['country']     = replace_improper_same($_REQUEST['shipping_country'] ?? '');
    $address                = json_encode($address);
    $ship_state             = strtoupper((string)($_REQUEST['shipping_state'] ?? ''));

    $sql_client = "SELECT * FROM clients WHERE `name` = '$client'";
    $query_client = $db->query($sql_client);
    $row_client = ($query_client && ($tmp = $query_client->fetch_assoc())) ? $tmp : [];

    $state = strtoupper((string)($row_client['state'] ?? ''));

    $so_no      = $_REQUEST['si_sales_order'] ?? [];
    if (!is_array($so_no)) { $so_no = []; }
    $sales_order= array();
    $e_len      = sizeof($so_no);
    for($i=0;$i<$e_len;$i++){
        $sales_order[] = $so_no[$i];
    }
    $sales_o=json_encode($sales_order);

    $q_no       = $_REQUEST['si_quotation[]'] ?? [];
    if (!is_array($q_no)) { $q_no = []; }
    $quotations = array();
    $e_len      = sizeof($q_no);
    for($i=0;$i<$e_len;$i++){
        $quotations[] = $q_no[$i];
    }
    $quotation  = json_encode($quotations);
    $tot_amount      = 0;

    $secondary_total = 0;
    $tax_value = 0;

    $items=array('product'=>array(),'desc'=>array(),'long_desc'=>array(),'group'=>array(),'quantity'=>array(),'unit'=>array(),'price'=>array(),'discount'=>array(),'hsn'=>array(),'tax'=>array());

    $tax            = array("cgst"=>'0', "sgst"=>'0', "igst"=>'0');

    for($i=0;$i<$l;$i++){
        $row_item = is_array($array[$i] ?? null) ? $array[$i] : [];
        if(($row_item['si_product_name'] ?? '') != '' && ($row_item['si_qty'] ?? '') != ''){

            if($series == 'PRIMARY')
            {
                $adjustment             = $row_item['si_adjustment'] ?? '';

                $items['product'][]     = replace_improper($row_item['si_product_name'] ?? '');
                $items['desc'][]        = replace_improper_textarea($row_item['si_product_description'] ?? '');
                $items['long_desc'][]   = replace_improper_textarea($row_item['si_product_add_description'] ?? '');
                $items['group'][]       = $row_item['si_display_make'] ?? '';
                $items['quantity'][]    = replace_improper($row_item['si_qty'] ?? '');
                $items['unit'][]        = replace_improper($row_item['si_unit'] ?? '');
                $items['price'][]       = replace_improper($row_item['si_rate'] ?? '');
                $items['discount'][]    = replace_improper($row_item['si_dsc'] ?? '');
                $items['hsn'][]         = replace_improper($row_item['si_hsn'] ?? '');
                $items['tax'][]         = replace_improper($row_item['si_tax'] ?? '');
                if($state == 'WEST BENGAL'){
                    $items['cgst'][]        = $row_item['si_cgst'] ?? 0;
                    $items['sgst'][]        = $row_item['si_sgst'] ?? 0;
                    $tax['cgst']            += (float)($row_item['si_cgst'] ?? 0);
                    $tax['sgst']            += (float)($row_item['si_sgst'] ?? 0);
                }else{
                    $items['igst'][]        = $row_item['si_igst'] ?? 0;
                    $tax['igst']            += (float)($row_item['si_igst'] ?? 0);
                }

                if($adjustment == '1'){
                    $ad_product     = $row_item['si_product_name'] ?? '';
                    $ad_qty         = (float)($row_item['si_qty'] ?? 0);
                    $adj_qty        = $ad_qty;

                    $ad_product2    = "\"".$ad_product."\"";
                    $sql_adj = "SELECT * FROM sales_invoice WHERE JSON_QUERY(`items`, '$.product') LIKE '%$ad_product2%' AND series = 'SECONDARY'";
                    $query_adj = $db->query($sql_adj);
                    if ($query_adj) {
                    while($row_adj = $query_adj->fetch_assoc()){

                        $adj_id = $row_adj['id'] ?? '';
                        $adj_items = json_decode($row_adj['items'] ?? '', true);
                        if (!is_array($adj_items)) { $adj_items = ['product'=>[]]; }
                        $adj_len = is_array($adj_items['product'] ?? null) ? sizeof($adj_items['product']) : 0;

                        for($adj_i=0;$adj_i<$adj_len;$adj_i++){
                            if(($adj_items['product'][$adj_i] ?? null) == $ad_product){
                                $eff = (float)($adj_items['effective_quantity'][$adj_i] ?? 0);
                                if($eff > $adj_qty){
                                    $adj_items['effective_quantity'][$adj_i] = $eff - $adj_qty;
                                    $adj_qty = 0;
                                    break;
                                }else{
                                    $adj_qty -= $eff;
                                    $adj_items['effective_quantity'][$adj_i] = 0;
                                }
                            }
                        }

                        $adj_items_json = $db->real_escape_string(json_encode($adj_items));

                        $sql_update = "UPDATE sales_invoice SET `items` = '$adj_items_json' WHERE `id` = '$adj_id'";
                        $db->query($sql_update);

                        if($adj_qty == 0){
                            break;
                        }
                    }
                    }
                }
            }
            else if($series == 'SECONDARY')
            {
                $items['product'][]     = replace_improper($row_item['si_product_name'] ?? '');
                $items['desc'][]        = replace_improper_textarea($row_item['si_product_description'] ?? '');
                $items['long_desc'][]   = replace_improper_textarea($row_item['si_product_add_description'] ?? '');
                $items['group'][]       = $row_item['si_display_make'] ?? '';
                $items['quantity'][]    = replace_improper($row_item['si_qty'] ?? '');
                $items['unit'][]        = replace_improper($row_item['si_unit'] ?? '');
                $items['price'][]       = replace_improper($row_item['si_rate'] ?? '');
                $items['discount'][]    = replace_improper($row_item['si_dsc'] ?? '');
                $items['hsn'][]         = replace_improper($row_item['si_hsn'] ?? '');
                $items['tax'][]         = replace_improper($row_item['si_tax'] ?? '');
                if($state == 'WEST BENGAL'){
                    $items['cgst'][]        = $row_item['si_cgst'] ?? 0;
                    $items['sgst'][]        = $row_item['si_sgst'] ?? 0;
                    $tax['cgst']            += (float)($row_item['si_cgst'] ?? 0);
                    $tax['sgst']            += (float)($row_item['si_sgst'] ?? 0);
                }else{
                    $items['igst'][]        = $row_item['si_igst'] ?? 0;
                    $tax['igst']            += (float)($row_item['si_igst'] ?? 0);
                }
            }
            else{
                $items['product'][]     = replace_improper($row_item['si_product_name'] ?? '');
                $items['desc'][]        = replace_improper_textarea($row_item['si_product_description'] ?? '');
                $items['long_desc'][]   = replace_improper_textarea($row_item['si_product_add_description'] ?? '');
                $items['group'][]       = $row_item['si_display_make'] ?? '';
                $items['quantity'][]    = replace_improper($row_item['si_qty'] ?? '');
                $items['effective_quantity'][]    = replace_improper($row_item['si_qty'] ?? '');
                $items['unit'][]        = replace_improper($row_item['si_unit'] ?? '');
                $items['price'][]       = replace_improper($row_item['si_rate'] ?? '');
                $items['discount'][]    = replace_improper($row_item['si_dsc'] ?? '');
                $items['hsn'][]         = replace_improper($row_item['si_hsn'] ?? '');
                $items['tax'][]         = '0';
                if($state == 'WEST BENGAL'){
                    $items['cgst'][]        = 0;
                    $items['sgst'][]        = 0;
                }else{
                    $items['igst'][]        = 0;
                }

                $total = (float)($row_item['si_qty'] ?? 0) * (float)($row_item['si_rate'] ?? 0);

                if (($row_item['si_dsc'] ?? '') != '') {
                    $total = $total - ((float)($row_item['si_qty'] ?? 0) * (float)($row_item['si_rate'] ?? 0)) * ((float)($row_item['si_dsc'] ?? 0) / 100);
                }

                $secondary_total += $total;
            }
        }
    }
    $item       = json_encode($items);

    $si_pf              = replace_improper_amount($_REQUEST['si_pf'] ?? '');
    $si_pf_cgst         = replace_improper_amount($_REQUEST['si_pf_cgst'] ?? '');
    $si_pf_sgst         = replace_improper_amount($_REQUEST['si_pf_sgst'] ?? '');
    $si_pf_igst         = replace_improper_amount($_REQUEST['si_pf_igst'] ?? '');

    $si_freight              = replace_improper_amount($_REQUEST['si_freight'] ?? '');
    $si_freight_cgst         = replace_improper_amount($_REQUEST['si_freight_cgst'] ?? '');
    $si_freight_sgst         = replace_improper_amount($_REQUEST['si_freight_sgst'] ?? '');
    $si_freight_igst         = replace_improper_amount($_REQUEST['si_freight_igst'] ?? '');

    $addons = array('freight'=>array('value'=>$si_freight,'cgst'=>'','sgst'=>'','igst'=>''),'pf'=>array('value'=>$si_pf,'cgst'=>'','sgst'=>'','igst'=>''),'roundoff'=>'');

    if($series == 'PRIMARY' || $series == 'ECOMMERCE')
    {
        if($state == 'WEST BENGAL'){
            $addons['freight']['cgst'] = $si_freight_cgst;
            $addons['freight']['sgst'] = $si_freight_sgst;
            $tax['cgst'] += (float)$si_freight_cgst;
            $tax['sgst'] += (float)$si_freight_sgst;

            $addons['pf']['cgst'] = $si_pf_cgst;
            $addons['pf']['sgst'] = $si_pf_sgst;
            $tax['cgst'] += (float)$si_pf_cgst;
            $tax['sgst'] += (float)$si_pf_sgst;
        }else{
            $addons['freight']['igst'] = $si_freight_igst;
            $tax['igst'] += (float)$si_freight_igst;

            $addons['pf']['igst'] = $si_pf_igst;
            $tax['igst'] += (float)$si_pf_igst;
        }
    }
    else
    {
        if($state == 'WEST BENGAL'){
            $addons['freight']['cgst'] = round($tax_value,2);
            $addons['freight']['sgst'] = round($tax_value,2);

            $addons['pf']['cgst'] = round($tax_value,2);
            $addons['pf']['sgst'] = round($tax_value,2);
        }
        else
        {
            $addons['pf']['igst'] = round($tax_value,2);
            $addons['freight']['igst'] = round($tax_value,2);
        }
    }

    if($tax['cgst'] != '')
        $tax['cgst'] = number_format((float)$tax['cgst'],2, '.', '');
    if($tax['sgst'] != '')
        $tax['sgst'] = number_format((float)$tax['sgst'],2, '.', '');
    if($tax['igst'] != '')
        $tax['igst'] = number_format((float)$tax['igst'],2, '.', '');

    $addons['roundoff'] = replace_improper_amount($_REQUEST['si_round'] ?? '');

    $tot_amount = replace_improper_amount($_REQUEST['si_total_final'] ?? '');
    $tot_amount = TrimTrailingZeroes(number_format((float)$tot_amount,2, '.', ''));

    $addon      = json_encode($addons);
    $tax_json   = json_encode($tax);

    $invoice_details_arr = array("buyer_order"=>"","order_date"=>"","payment_terms"=>"","delivery_terms"=>"","other_ref"=>"","despatch_medium"=>"","despatch_doc_no"=>"","despatch_date"=>"","despatch_destination"=>"");

    $invoice_details_arr["buyer_order"]         = replace_improper_same($_REQUEST['buyer_order_no'] ?? '');
    $buyer_order_date = $_REQUEST['buyer_order_date'] ?? '';
    if($buyer_order_date != '')
        $invoice_details_arr["order_date"]      = date('Y-m-d', strtotime($buyer_order_date));
    else
        $invoice_details_arr["order_date"]      = '';

    $notes      = replace_improper_textarea($_REQUEST['notes'] ?? '');
    $invoice_details_arr["payment_terms"]       = replace_improper_same($_REQUEST['terms_payment'] ?? '');
    $invoice_details_arr["delivery_terms"]      = replace_improper_same($_REQUEST['terms_delivery'] ?? '');
    $invoice_details_arr["other_ref"]           = replace_improper_same($_REQUEST['other_ref'] ?? '');
    $invoice_details_arr["despatch_medium"]     = replace_improper_same($_REQUEST['despatch_medium'] ?? '');
    $invoice_details_arr["despatch_doc_no"]     = replace_improper_same($_REQUEST['despatch_doc_no'] ?? '');
    $despatch_date = $_REQUEST['despatch_date'] ?? '';
    if($despatch_date != '')
        $invoice_details_arr["despatch_date"]   = date('Y-m-d', strtotime($despatch_date));
    else
        $invoice_details_arr["despatch_date"]   = '';
    $invoice_details_arr["despatch_destination"]= replace_improper_same($_REQUEST['despatch_destination'] ?? '');

    $invoice_details = json_encode($invoice_details_arr);

    $status=0;

    if($id == '')
    {
        $row_counter_arr = null;
        if($series == 'PRIMARY'){
            $sql_counter = "SELECT * FROM counter WHERE `key` = 'sales_invoice'";
            $query_counter = $db->query($sql_counter);
            if ($query_counter && $query_counter->num_rows > 0) {
                $row_counter = $query_counter->fetch_assoc();
                $row_counter_arr = json_decode($row_counter['value'] ?? '', true);
                if (is_array($row_counter_arr) && isset($row_counter_arr['prefix'][0], $row_counter_arr['number'][0], $row_counter_arr['postfix'][0])) {
                    $order_no = $row_counter_arr['prefix'][0].str_pad($row_counter_arr['number'][0],4,'0', STR_PAD_LEFT).$row_counter_arr['postfix'][0];
                    $row_counter_arr['number'][0] = $row_counter_arr['number'][0] + 1;
                }
            }
        }
        else if($series == 'SECONDARY'){
            $sql_counter = "SELECT * FROM counter WHERE `key` = 'Secondary'";
            $query_counter = $db->query($sql_counter);
            if ($query_counter && $query_counter->num_rows > 0) {
                $row_counter = $query_counter->fetch_assoc();
                $row_counter_arr = json_decode($row_counter['value'] ?? '', true);
                if (is_array($row_counter_arr) && isset($row_counter_arr['prefix'][0], $row_counter_arr['number'][0], $row_counter_arr['postfix'][0])) {
                    $order_no = $row_counter_arr['prefix'][0].str_pad($row_counter_arr['number'][0],4,'0', STR_PAD_LEFT).$row_counter_arr['postfix'][0];
                    $row_counter_arr['number'][0] = $row_counter_arr['number'][0] + 1;
                }
            }
        }
        else{
            $sql_counter = "SELECT * FROM counter WHERE `key` = 'secondary'";
            $query_counter = $db->query($sql_counter);
            if ($query_counter && $query_counter->num_rows > 0) {
                $row_counter = $query_counter->fetch_assoc();
                $row_counter_arr = json_decode($row_counter['value'] ?? '', true);
                if (is_array($row_counter_arr) && isset($row_counter_arr['prefix'][0], $row_counter_arr['number'][0], $row_counter_arr['postfix'][0])) {
                    $order_no = $row_counter_arr['prefix'][0].$row_counter_arr['number'][0].$row_counter_arr['postfix'][0];
                    $row_counter_arr['number'][0] = $row_counter_arr['number'][0] + 1;
                }
            }
        }

        $sql = "INSERT INTO sales_invoice (`client_name`,`mobile`,`si_no`,`series`,`si_date`,`so_no`,`shipping`,`state`,`invoice_details`,`items`,`addons`,`hsn_table`,`total`,`notes`,`tax`,`status`,`log_user`,`log_date`) VALUES ('$client','$mobile','$order_no','$series', '$invoice_date','$sales_o','$address','$ship_state','$invoice_details','$item','$addon','1','$tot_amount','$notes','$tax_json','$status','$log_user','$log_date')";
        $query = $db->query($sql);

        if($query===true)
        {
            if(isset($row_counter_arr) && is_array($row_counter_arr)){
            if($series == 'PRIMARY'){
                $counter_array = json_encode($row_counter_arr);
                $sql_counter = "UPDATE counter SET `value` = '$counter_array' WHERE `key` = 'sales_invoice'";
                $db->query($sql_counter);
            }
            else if($series == 'SECONDARY'){
                $counter_array = json_encode($row_counter_arr);
                $sql_counter = "UPDATE counter SET `value` = '$counter_array' WHERE `key` = 'Secondary'";
                $db->query($sql_counter);
            }
            else{
                $counter_array = json_encode($row_counter_arr);
                $sql_counter = "UPDATE counter SET `value` = '$counter_array' WHERE `key` = 'secondary'";
                $db->query($sql_counter);
            }
            }

            $validator['success'] = true;
            $validator['messages'] = "Successfully Added";
            $validator['si'] = $order_no;
            createWhatsAppQueueEntry($client, $order_no, $invoice_date, $mobile, $tot_amount, $series, $log_user);
        }
        else
        {
            $validator['success'] = false;
            $validator['messages'] = "There was some error saving the records";
        }
    }
    else
    {
        $sql = "UPDATE sales_invoice SET `client_name` = '$client',`mobile`='$mobile',`series` = '$series', `si_no`='$order_no',`si_date`='$invoice_date', `so_no`='$sales_o',`shipping`='$address',`state`='$ship_state',`invoice_details`='$invoice_details',`items`='$item',`addons`='$addon', `total` = '$tot_amount',`notes`= '$notes', `tax` = '$tax_json',`cancelled`='0',`log_user`='$log_user',`log_date`='$log_date' WHERE `id`='$id'";
        $query = $db->query($sql);

        if($query===true)
        {
            $validator['success'] = true;
            $validator['messages'] = "Successfully Updated";
            $validator['si'] = $order_no;
        }
        else
        {
            $validator['success'] = false;
            $validator['messages'] = "There was some error saving the records";
        }
    }

    echo json_encode($validator);
?>
