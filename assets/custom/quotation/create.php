<?php
    include ("../connect.php");
    include ("../php_replace_improper.php");

    session_start();

    $validator = array("success"=>false, "messages"=>"There was some error saving the records", "q_no"=>"");

    $q_id = $_REQUEST['q_id'] ?? '';

    $log_user = $_SESSION['username'] ?? '';
    $log_date = date('Y-m-d', strtotime("today"));

    $client         = replace_improper_same($_REQUEST['q_client'] ?? '');

    $sql_pull = "SELECT * FROM clients WHERE name = '$client'";
    $query_pull = $db->query($sql_pull);
    $row_pull = ($query_pull && $query_pull->num_rows > 0) ? $query_pull->fetch_assoc() : null;
    $state = $row_pull['state'] ?? '';

    $quotation_no   = replace_improper_same($_REQUEST['quotation_no'] ?? '');
    $quotation_date_raw = $_REQUEST['quotation_date'] ?? '';
    $quotation_date = ($quotation_date_raw !== '') ? date('Y-m-d', strtotime((string)$quotation_date_raw)) : '';
    $mobile         = replace_improper_same($_REQUEST['mobile'] ?? '');
    $address_1      = replace_improper_same(strtoupper((string)($_REQUEST['address_1'] ?? '')));
    $address_2      = replace_improper_same(strtoupper((string)($_REQUEST['address_2'] ?? '')));
    $country        = replace_improper_same(strtoupper((string)($_REQUEST['country'] ?? '')));
    $state          = replace_improper_same(strtoupper((string)($_REQUEST['state'] ?? '')));
    $city           = replace_improper_same(strtoupper((string)($_REQUEST['city'] ?? '')));
    $pincode        = replace_improper_same($_REQUEST['pincode'] ?? '');

    if($state == '')
    {
        $state          = replace_improper_same(strtoupper((string)($_REQUEST['q_state'] ?? '')));
    }

    $address        = array("address_1"=>$address_1, "address_2"=>$address_2, "country"=>$country, "state"=>$state, "city"=>$city, "pincode"=>$pincode);
    $address        = json_encode($address);

    $enquiry_no     = $_REQUEST['q_enquiry_no'] ?? [];
    if (!is_array($enquiry_no)) { $enquiry_no = []; }
    $cl_enquiry_no  = json_decode($_REQUEST['q_cl_enquiry_no'] ?? '', true);
    if(!is_array($cl_enquiry_no)) $cl_enquiry_no = [];
    $enquiry_date   = json_decode($_REQUEST['q_enquiry_date'] ?? '', true);
    if(!is_array($enquiry_date)) $enquiry_date = [];

    $prices         = replace_improper_same($_REQUEST['prices'] ?? '');
    $pf             = replace_improper_same($_REQUEST['pf'] ?? '');
    $freight        = replace_improper_same($_REQUEST['freight'] ?? '');
    $delivery       = replace_improper_same($_REQUEST['delivery'] ?? '');
    $payment        = replace_improper_same($_REQUEST['payment'] ?? '');
    $validity       = replace_improper_same($_REQUEST['validity'] ?? '');
    $remarks        = replace_improper_same($_REQUEST['remarks'] ?? '');

    $discount       = replace_improper_same($_REQUEST['q_tot_discount'] ?? '');
    $q_pf           = replace_improper_amount($_REQUEST['q_pf'] ?? '');    
    $q_pf_cgst      = replace_improper_amount($_REQUEST['q_pf_cgst'] ?? '');    
    $q_pf_sgst      = replace_improper_amount($_REQUEST['q_pf_sgst'] ?? '');    
    $q_pf_igst      = replace_improper_amount($_REQUEST['q_pf_igst'] ?? '');    
    $q_freight      = replace_improper_amount($_REQUEST['q_freight'] ?? '');     
    $q_freight_cgst = replace_improper_amount($_REQUEST['q_freight_cgst'] ?? '');     
    $q_freight_sgst = replace_improper_amount($_REQUEST['q_freight_sgst'] ?? '');     
    $q_freight_igst = replace_improper_amount($_REQUEST['q_freight_igst'] ?? ''); 

    $tax            = array("cgst"=>'0', "sgst"=>'0', "igst"=>'0');

    $status         = 0;
    if(($_REQUEST['quotation_status'] ?? '') == 1)
        $status         = 1;

    $array = $_REQUEST['quotation'] ?? [];
    if (!is_array($array)) { $array = []; }
    $l = sizeof($array);

    $items=array('product'=>array(),'desc'=>array(),'long_desc'=>array(),'group'=>array(),'quantity'=>array(),'unit'=>array(),'price'=>array(),'discount'=>array(),'hsn'=>array(),'tax'=>array());

    for($i=0;$i<$l;$i++){
        $row = is_array($array[$i] ?? null) ? $array[$i] : [];
        if(($row['q_product_name'] ?? '') != '' && ($row['q_qty'] ?? '') != ''){

            $items['product'][]     = replace_improper($row['q_product_name'] ?? '');
            $items['desc'][]        = replace_improper_same($row['q_product_description'] ?? '');
            $items['long_desc'][]   = replace_improper_textarea($row['q_product_add_description'] ?? '');
            $items['group'][]       = $row['q_display_make'][0] ?? '';
            $items['quantity'][]    = replace_improper_same($row['q_qty'] ?? '');
            $items['unit'][]        = replace_improper($row['q_unit'] ?? '');
            $items['price'][]       = replace_improper_amount($row['q_rate'] ?? '');
            $items['discount'][]    = replace_improper_same($row['q_dsc'] ?? '');
            $items['hsn'][]         = replace_improper_same($row['q_hsn'] ?? '');
            $items['tax'][]         = replace_improper_same($row['q_tax'] ?? '');
            if($state == 'WEST BENGAL'){
                $items['cgst'][]        = $row['q_cgst'] ?? 0;
                $items['sgst'][]        = $row['q_sgst'] ?? 0;
                $tax['cgst']            += (float)($row['q_cgst'] ?? 0);
                $tax['sgst']            += (float)($row['q_sgst'] ?? 0);
            }else{
                $items['igst'][]        = $row['q_igst'] ?? 0;
                $tax['igst']            += (float)($row['q_igst'] ?? 0);
            } 
        }
    }
    $item=json_encode($items);

    
    $quotations = array('enquiry_no'=>array(),'cl_enquiry_no'=>array(),'enquiry_date'=>array());
    $e_len = sizeof($enquiry_no);
    for($i=0;$i<$e_len;$i++){
        $quotations['enquiry_no'][]     = $enquiry_no[$i];
        $quotations['cl_enquiry_no'][]  = $cl_enquiry_no[$i] ?? '';
        $quotations['enquiry_date'][]   = $enquiry_date[$i] ?? '';
    }

    $terms      = array('prices'=>$prices,'pf'=>$pf,'freight'=>$freight,'delivery'=>$delivery,'payment'=>$payment,'validity'=>$validity,'remarks'=>$remarks);
    $addons = array('freight'=>array('value'=>$q_freight,'cgst'=>'','sgst'=>'','igst'=>''),'pf'=>array('value'=>$q_pf,'cgst'=>'','sgst'=>'','igst'=>''),'roundoff'=>'');

    if($state == 'WEST BENGAL'){

        $addons['freight']['cgst'] = $q_freight_cgst;
        $addons['freight']['sgst'] = $q_freight_sgst;
        $tax['cgst'] += (float)$q_freight_cgst;
        $tax['sgst'] += (float)$q_freight_sgst;

        $addons['pf']['cgst'] = $q_pf_cgst;
        $addons['pf']['sgst'] = $q_pf_sgst;
        $tax['cgst'] += (float)$q_pf_cgst;
        $tax['sgst'] += (float)$q_pf_sgst;

    }else{

        $addons['freight']['igst'] = $q_freight_igst;
        $tax['igst'] += (float)$q_freight_igst;

        $addons['pf']['igst'] = $q_pf_igst;
        $tax['igst'] += (float)$q_pf_igst;

    }

    $tot_amount = replace_improper_amount($_REQUEST['q_total_final'] ?? '');
    $addons['roundoff'] = replace_improper_amount($_REQUEST['q_round'] ?? '');

    $addon      = json_encode($addons);

    $quotation  = json_encode($quotations);
    $term       = json_encode($terms);
    $tax_json   = json_encode($tax);

    if($q_id == '')
    {
        $sql_counter = "SELECT * FROM counter WHERE `key` = 'quotation'";
        $query_counter = $db->query($sql_counter);
        if ($query_counter && $query_counter->num_rows > 0) {
        $row_counter = $query_counter->fetch_assoc();
        $row_counter_arr = json_decode($row_counter['value'] ?? '', true);

        if(is_array($row_counter_arr) && isset($row_counter_arr['prefix'][0], $row_counter_arr['number'][0], $row_counter_arr['postfix'][0])){
            $quotation_no = $row_counter_arr['prefix'][0].str_pad((string)$row_counter_arr['number'][0],4,'0', STR_PAD_LEFT).$row_counter_arr['postfix'][0];
            $row_counter_arr['number'][0] = $row_counter_arr['number'][0] + 1;

        $sql = "INSERT INTO quotation (`client`,`mobile`,`quotation_no`,`quotation_date`,`quotation_top`,`items`,`address`,`addons`,`terms`,`display_totals`,`display_hsn`,`total`,`tax`,`status`,`log_user`,`log_date`) VALUES ('$client','$mobile','$quotation_no', '$quotation_date','$quotation','$item','$address','$addon','$term','1','1','$tot_amount','$tax_json','$status','$log_user','$log_date')";
        $query = $db->query($sql);

        if($query===true)
        {
                $counter_array = json_encode($row_counter_arr);
                $sql_counter = "UPDATE counter SET `value` = '$counter_array' WHERE `key` = 'quotation'";
                $query_counter = $db->query($sql_counter);

            $validator['success'] = true;
            $validator['messages'] = "Successfully Added";
            $validator['q_no'] = $quotation_no;
        }
        else
        {
            $validator['success'] = false;
            $validator['messages'] = "There was some error saving the records";

        }
        } else {
            $validator['success'] = false;
            $validator['messages'] = "Quotation counter is not configured correctly.";
        }
        } else {
            $validator['success'] = false;
            $validator['messages'] = "Quotation counter not found.";
        }
    }
    else{
        $sql = "UPDATE quotation SET `client` = '$client',`mobile` = '$mobile', `quotation_no`='$quotation_no', `quotation_date`='$quotation_date', `quotation_top`='$quotation', `items`='$item', `address`='$address', `addons`='$addon', `total` = '$tot_amount', `tax` = '$tax_json', `terms`='$term', `status`='$status', `log_user`='$log_user', `log_date`='$log_date' WHERE `id`='$q_id'";
        $query = $db->query($sql);

        if($query===true)
        {
            $validator['success'] = true;
            $validator['messages'] = "Successfully Updated";
            $validator['q_no'] = $quotation_no;
        }
        else
        {
            $validator['success'] = false;
            $validator['messages'] = "There was some error saving the records";

        }
    }

    echo json_encode($validator);
?>
