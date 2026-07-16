<?php
    // ini_set('display_errors', 1);
    include ("../connect.php");
    include ("../php_replace_improper.php");

    $request = file_get_contents('php://input');
    $input = json_decode($request);
    $array = json_decode($request, true);

    if (!$input || !is_array($array)) {
        echo json_encode(array("success" => false, "messages" => "Invalid request"));
        exit;
    }

    session_start();

    $validator = array("success"=>true, "messages"=>"There was some error saving the records","pi"=>"");

    $pi_qd = $input->edit_pq_id ?? '';
    $log_user = $_SESSION['username'] ?? '';
    $log_date = date('Y-m-d', strtotime("today"));

    $order_no = $input->purchase_quotation_no ?? '';
    $series = $input->series ?? '';
    $mobile = $input->mobile ?? '';
    $pq_date_raw = $input->purchase_invoice_date ?? '';
    $order_date = ($pq_date_raw !== '') ? date('Y-m-d', strtotime($pq_date_raw)) : '';
    $supplier = replace_improper($input->pi_supplier ?? '');
    $po_no = $input->pi_purchase_order ?? [];
    if (!is_array($po_no)) { $po_no = []; }

    $pi_pf = replace_improper_amount($input->pi_pf ?? '');
    $pi_pf_cgst = replace_improper_amount($input->pi_pf_cgst ?? '');
    $pi_pf_sgst = replace_improper_amount($input->pi_pf_sgst ?? '');
    $pi_pf_igst = replace_improper_amount($input->pi_pf_igst ?? '');

    $pi_freight = replace_improper_amount($input->pi_freight ?? '');
    $pi_freight_cgst = replace_improper_amount($input->pi_freight_cgst ?? '');
    $pi_freight_sgst = replace_improper_amount($input->pi_freight_sgst ?? '');
    $pi_freight_igst = replace_improper_amount($input->pi_freight_igst ?? '');

    $pi_tcs = replace_improper_amount($input->pi_tcs ?? '');

    // Fetch supplier details
    $sql_pull = "SELECT * FROM suppliers WHERE name = '$supplier'";
    $query_pull = $db->query($sql_pull);
    $row_pull = ($query_pull && ($tmp = $query_pull->fetch_assoc())) ? $tmp : [];
    $state = strtoupper((string)($row_pull['state'] ?? ''));

    $pi_pf = str_replace(",","",$pi_pf);
    $pi_freight = str_replace(",","",$pi_freight);
    $pi_tcs = str_replace(",","",$pi_tcs);

    $tot_amount = replace_improper_amount($input->pi_total_final ?? '');
    $tot_amount = TrimTrailingZeroes(number_format((float)$tot_amount, 2, '.', ''));

    $tax = array("cgst"=>'', "sgst"=>'', "igst"=>'');

    // Initialize items array
    $items = array('product'=>array(),'desc'=>array(),'long_desc'=>array(),'group'=>array(),'quantity'=>array(),'unit'=>array(),'price'=>array(),'discount'=>array(),'hsn'=>array(),'tax'=>array());
    $group = 0;

    // Shipping address
    $address = array('address1'=>'', 'address2'=>'', 'address3'=>'');
    $address['address1'] = replace_improper($input->shipping_add_1 ?? '');
    $address['address2'] = replace_improper($input->shipping_add_2 ?? '');
    $address['address3'] = replace_improper($input->shipping_add_3 ?? '');
    $address = json_encode($address);

    $secondary_total = 0;

    $purchase_invoice_items = $array['purchase_invoice'] ?? [];
    if (!is_array($purchase_invoice_items)) { $purchase_invoice_items = []; }
    foreach($purchase_invoice_items as $item) {
        if($item['pi_product_name'] != '' && $item['pi_qty'] != '') {
            $items['product'][] = replace_improper($item['pi_product_name']);
            $items['desc'][] = replace_improper($item['pi_product_description']);
            $items['long_desc'][] = replace_improper_textarea($item['pi_product_add_description']);
            $items['group'][] = $item['pi_display_make'];
            $items['quantity'][] = replace_improper($item['pi_qty']);
            $items['unit'][] = replace_improper($item['pi_unit']);
            $items['price'][] = replace_improper($item['pi_rate']);
            $items['discount'][] = replace_improper($item['pi_dsc']);
            $items['hsn'][] = replace_improper($item['pi_hsn']);
            $items['tax'][] = replace_improper($item['pi_tax']);

            if($state == 'WEST BENGAL') {
                $items['cgst'][] = $item['pi_cgst'];
                $items['sgst'][] = $item['pi_sgst'];
                $tax['cgst'] += $item['pi_cgst'];
                $tax['sgst'] += $item['pi_sgst'];
            } else {
                $items['igst'][] = $item['pi_igst'];
                $tax['igst'] += $item['pi_igst'];
            }

            $secondary_total += $item['pi_gross_pr'];
        }
    }
    $item = json_encode($items);

    // Addon calculations
    $addons = array('freight'=>array('value'=>$pi_freight,'cgst'=>'','sgst'=>'','igst'=>''),'pf'=>array('value'=>$pi_pf,'cgst'=>'','sgst'=>'','igst'=>''),'roundoff'=>'','tcs'=>$pi_tcs);

    if($state == 'WEST BENGAL') {
        $addons['freight']['cgst'] = $pi_freight_cgst;
        $addons['freight']['sgst'] = $pi_freight_sgst;
        $tax['cgst'] += $pi_freight_cgst;
        $tax['sgst'] += $pi_freight_sgst;

        $addons['pf']['cgst'] = $pi_pf_cgst;
        $addons['pf']['sgst'] = $pi_pf_sgst;
        $tax['cgst'] += $pi_pf_cgst;
        $tax['sgst'] += $pi_pf_sgst;
    } else {
        $addons['freight']['igst'] = $pi_freight_igst;
        $tax['igst'] += $pi_freight_igst;

        $addons['pf']['igst'] = $pi_pf_igst;
        $tax['igst'] += $pi_pf_igst;
    }

    if($tax['cgst'] != '') {
        $tax['cgst'] = number_format((float)$tax['cgst'],2, '.', '');
    }
    if($tax['sgst'] != '') {
        $tax['sgst'] = number_format((float)$tax['sgst'],2, '.', '');
    }
    if($tax['igst'] != '') {
        $tax['igst'] = number_format((float)$tax['igst'],2, '.', '');
    }

    $addons['roundoff'] = $input->pi_round ?? '';
    $addon = json_encode($addons);
    $tax_json = json_encode($tax);

    // Process purchase order array
    $purchase_order = array();
    $e_len = sizeof($po_no);
    for($i=0;$i<$e_len;$i++) {
        $purchase_order[] = $po_no[$i];
    }
    $purchase_o = json_encode($purchase_order);

    // Handle file upload
    $filePath = null;
    if (isset($_FILES['quotation_file']) && $_FILES['quotation_file']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['quotation_file']['tmp_name'];
        $fileName = $_FILES['quotation_file']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Create a folder based on the order number
        $uploadFileDir = '../../uploads/p_quotations/' . $order_no . '/';
        if (!file_exists($uploadFileDir)) {
            mkdir($uploadFileDir, 0777, true);
        }

        $newFilePath = $uploadFileDir . time() . '_' . $fileName;
        if (move_uploaded_file($fileTmpPath, $newFilePath)) {
            $filePath = $newFilePath;
        }
    }

    if($pi_qd == '') {
        // Insert new purchase quotation
        $sql = "INSERT INTO purchase_quotation (`supplier_name`,`mobile`,`series`,`pq_no`,`pi_date`,`po_no`,`shipping`,`items`,`addons`,`total`,`tax`,`status`,`log_user`,`log_date`,`file_path`)
                VALUES ('$supplier','$mobile','$series','$order_no', '$order_date','$purchase_o','$address','$item','$addon','$tot_amount','$tax_json',0,'$log_user','$log_date','$filePath')";
        $query = $db->query($sql);

        if($query === true) {
            $validator['success'] = true;
            $validator['messages'] = "Successfully Added";
            $validator['pi'] = $order_no;
        } else {
            $validator['success'] = false;
            $validator['messages'] = "There was some error saving the records";
        }
    } else {
        // Update existing purchase quotation
        $sql = "UPDATE purchase_quotation SET `supplier_name` = '$supplier', `mobile` = '$mobile', `series` = '$series', `pq_no` = '$order_no', `pi_date` = '$order_date', `po_no` = '$purchase_o', `shipping` = '$address', `items` = '$item', `addons` = '$addon', `total` = '$tot_amount', `tax` = '$tax_json', `log_user` = '$log_user', `log_date` = '$log_date', `file_path` = '$filePath' WHERE `id` = '$pi_qd'";
        $query = $db->query($sql);

        if($query === true) {
            $validator['success'] = true;
            $validator['messages'] = "Successfully Updated";
            $validator['pi'] = $order_no;
        } else {
            $validator['success'] = false;
            $validator['messages'] = "There was some error saving the records";
        }
    }

    echo json_encode($validator);

    function TrimTrailingZeroes($nbr) {
        return strpos($nbr,'.') !== false ? rtrim(rtrim($nbr,'0'),'.') : $nbr;
    }
?>
