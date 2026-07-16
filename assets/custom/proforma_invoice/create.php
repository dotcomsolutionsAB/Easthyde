<?php
    include ("../connect.php");
    include ("../php_replace_improper.php");

    session_start();

    $validator      = array("success"=>true, "messages"=>"There was some error saving the records", "so"=>"");

    $pr_id          = $_REQUEST['edit_pr_id'] ?? '';

    $log_user       = $_SESSION['username'] ?? '';
    $log_date       = date('Y-m-d', strtotime("today"));

    $client         = replace_improper($_REQUEST['pr_client'] ?? '');

    $sql_pull = "SELECT * FROM clients WHERE name = '$client'";
    $query_pull = $db->query($sql_pull);
    $row_pull = ($query_pull && $query_pull->num_rows > 0) ? $query_pull->fetch_assoc() : null;
    $state = $row_pull['state'] ?? '';

    $client_so_no   = $_REQUEST['client_so_no'] ?? '';
    $mobile   = $_REQUEST['mobile'] ?? '';


    $address_1      = replace_improper_same(strtoupper((string)($_REQUEST['address_1'] ?? '')));
    $address_2      = replace_improper_same(strtoupper((string)($_REQUEST['address_2'] ?? '')));
    $country        = replace_improper_same(strtoupper((string)($_REQUEST['country'] ?? '')));
    if($state == '')
    {
        $state          = replace_improper_same(strtoupper((string)($_REQUEST['state'] ?? '')));
    }
    $city           = replace_improper_same(strtoupper((string)($_REQUEST['city'] ?? '')));
    $pincode        = replace_improper_same($_REQUEST['pincode'] ?? '');

    $address        = array("address_1"=>$address_1, "address_2"=>$address_2, "country"=>$country, "state"=>$state, "city"=>$city, "pincode"=>$pincode);
    $address        = json_encode($address);
    
    $pr_date_raw    = $_REQUEST['pr_date'] ?? '';
    $order_date     = ($pr_date_raw !== '') ? date('Y-m-d', strtotime((string)$pr_date_raw)) : '';
    $order_no        = $_REQUEST['pr_no'] ?? '';
    $so_no           = $_REQUEST['pr_sales_order'] ?? [];
    $pr_pf                = replace_improper_amount($_REQUEST['pr_pf'] ?? '');    
    $pr_pf_cgst           = replace_improper_amount($_REQUEST['pr_pf_cgst'] ?? '');    
    $pr_pf_sgst           = replace_improper_amount($_REQUEST['pr_pf_sgst'] ?? '');    
    $pr_pf_igst           = replace_improper_amount($_REQUEST['pr_pf_igst'] ?? '');    
    $pr_freight           = replace_improper_amount($_REQUEST['pr_freight'] ?? '');  
    $pr_freight_cgst      = replace_improper_amount($_REQUEST['pr_freight_cgst'] ?? '');  
    $pr_freight_sgst      = replace_improper_amount($_REQUEST['pr_freight_sgst'] ?? '');  
    $pr_freight_igst      = replace_improper_amount($_REQUEST['pr_freight_igst'] ?? '');  

    $tax            = array("cgst"=>'0', "sgst"=>'0', "igst"=>'0');

    $array          = $_REQUEST['proforma_invoice'] ?? [];
    $l              = sizeof($array);

    $items=array('product'=>array(),'desc'=>array(),'long_desc'=>array(),'group'=>array(),'quantity'=>array(),'received'=>array(),'unit'=>array(),'price'=>array(),'discount'=>array(),'hsn'=>array(),'tax'=>array());

    $group=0;

    for($i=0;$i<$l;$i++){
        if($array[$i]['pr_product_name'] != '' && $array[$i]['pr_qty'] != ''){

            $items['product'][]     = replace_improper($array[$i]['pr_product_name']);
            $items['desc'][]        = replace_improper($array[$i]['pr_product_description']);
            $items['long_desc'][]   = replace_improper_textarea($array[$i]['pr_product_add_description']);
            $items['group'][]       = $array[$i]['pr_display_make'][0] ?? '';
            $items['quantity'][]    = replace_improper($array[$i]['pr_qty']);
            $items['received'][]    = '0';
            $items['unit'][]        = replace_improper($array[$i]['pr_unit']);
            $items['price'][]       = replace_improper($array[$i]['pr_rate']);
            $items['discount'][]    = replace_improper($array[$i]['pr_dsc']);
            $items['hsn'][]         = replace_improper($array[$i]['pr_hsn']);
            $items['tax'][]         = replace_improper($array[$i]['pr_tax']);
            if($state == 'WEST BENGAL'){
                $items['cgst'][]        = $array[$i]['pr_cgst'];
                $items['sgst'][]        = $array[$i]['pr_sgst'];
                $tax['cgst']            += $array[$i]['pr_cgst'];
                $tax['sgst']            += $array[$i]['pr_sgst'];
            }else{
                $items['igst'][]        = $array[$i]['pr_igst'];
                $tax['igst']            +=$array[$i]['pr_igst'];
            } 

        }
    }
    $item=json_encode($items);

    $status=0;
    $addons = array('freight'=>array('value'=>$pr_freight,'cgst'=>'','sgst'=>'','igst'=>''),'pf'=>array('value'=>$pr_pf,'cgst'=>'','sgst'=>'','igst'=>''),'roundoff'=>'');

    if($state == 'WEST BENGAL'){

        $addons['freight']['cgst'] = $pr_freight_cgst;
        $addons['freight']['sgst'] = $pr_freight_sgst;
        $tax['cgst'] += $pr_freight_cgst;
        $tax['sgst'] += $pr_freight_sgst;

        $addons['pf']['cgst'] = $pr_pf_cgst;
        $addons['pf']['sgst'] = $pr_pf_sgst;
        $tax['cgst'] += $pr_pf_cgst;
        $tax['sgst'] += $pr_pf_sgst;

    }else{

        $addons['freight']['igst'] = $pr_freight_igst;
        $tax['igst'] += $pr_freight_igst;

        $addons['pf']['igst'] = $pr_pf_igst;
        $tax['igst'] += $pr_pf_igst;

    }

    $sales_order=array();
    $e_len = sizeof($so_no);
    for($i=0;$i<$e_len;$i++){
        $sales_order[] = $so_no[$i];
    }
    $sales_order=json_encode($sales_order);

    $tot_amount = replace_improper_amount($_REQUEST['pr_total_final'] ?? '');
    $addons['roundoff'] = replace_improper_amount($_REQUEST['pr_round'] ?? '');

    $addon      = json_encode($addons);
    $tax_json   = json_encode($tax);

    if($pr_id == '')
    {
        $sql_counter = "SELECT * FROM counter WHERE `key` = 'proforma'";
        $query_counter = $db->query($sql_counter);
        $row_counter = ($query_counter && $query_counter->num_rows > 0) ? $query_counter->fetch_assoc() : null;
        $row_counter_arr = ($row_counter && isset($row_counter['value'])) ? json_decode($row_counter['value'], true) : null;

        if(is_array($row_counter_arr) && isset($row_counter_arr['prefix'][0]) && isset($row_counter_arr['number'][0]) && isset($row_counter_arr['postfix'][0])){
            $order_no = $row_counter_arr['prefix'][0].str_pad($row_counter_arr['number'][0],3,'0', STR_PAD_LEFT).$row_counter_arr['postfix'][0];
            $row_counter_arr['number'][0] = $row_counter_arr['number'][0] + 1;
        }

        $sql = "INSERT INTO proforma (`client_name`,`mobile`,`pr_no`,`pr_date`,`client_so_no`,`so_no`,`items`,`address`,`addons`,`total`,`tax`,`status`,`log_user`,`log_date`) VALUES ('$client','$mobile','$order_no', '$order_date','$client_so_no','$sales_order','$item','$address','$addon','$tot_amount','$tax_json','$status','$log_user','$log_date')";
        $query = $db->query($sql);

        if($query===true)
        {
            if(is_array($row_counter_arr) && isset($row_counter_arr['prefix'][0])){
                $counter_array = json_encode($row_counter_arr);
                $sql_counter = "UPDATE counter SET `value` = '$counter_array' WHERE `key` = 'proforma'";
                $query_counter = $db->query($sql_counter);
            }

            $validator['success'] = true;
            $validator['messages'] = "Successfully Added";
            $validator['so'] = $order_no;
        }
        else
        {
            $validator['success'] = false;
            $validator['messages'] = "There was some error saving the records";
            $validator['sql'] = $sql;


        }
    }
    else
    {
        $sql = "UPDATE proforma SET `client_name` = '$client',`mobile`='$mobile', `pr_no`='$order_no',`pr_date`='$order_date', `client_so_no` = '$client_so_no', `so_no`='$sales_order',`items`='$item',`address`='$address',`addons`='$addon', `total` = '$tot_amount', `tax` = '$tax_json',`status`='$status',`log_user`='$log_user',`log_date`='$log_date' WHERE `id`='$pr_id'";
        $query = $db->query($sql);

        if($query===true)
        {
            $validator['success'] = true;
            $validator['messages'] = "Successfully Updated";
            $validator['so'] = $order_no;
        }
        else
        {
            $validator['success'] = false;
            $validator['messages'] = "There was some error saving the records";
            $validator['sql'] = $sql;

        }
    }

    echo json_encode($validator);
?>
