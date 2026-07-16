<?php
    include ("../connect.php");
    include ("../php_replace_improper.php");
    //ini_set('display_errors', 1);

    session_start();

    $validator      = array("success"=>true, "messages"=>"There was some error saving the records", "so"=>"");

    $so_id          = $_REQUEST['edit_so_id'] ?? '';

    $log_user       = $_SESSION['username'] ?? '';
    $log_date       = date('Y-m-d', strtotime("today"));

    $client         = replace_improper($_REQUEST['so_client'] ?? '');

    $sql_pull = "SELECT * FROM clients WHERE name = '$client'";
    $query_pull = $db->query($sql_pull);
    $row_pull = ($query_pull && $query_pull->num_rows > 0) ? $query_pull->fetch_assoc() : null;
    $state = $row_pull['state'] ?? '';
    
    $sales_date_raw = $_REQUEST['sales_date'] ?? '';
    $order_date     = ($sales_date_raw !== '') ? date('Y-m-d', strtotime((string)$sales_date_raw)) : '';
    $order_no       = $_REQUEST['sales'] ?? '';
    $mobile         = $_REQUEST['mobile'] ?? '';
    $q_no           = $_REQUEST['so_quotation'] ?? [];
    $collected      = $_REQUEST['so_collected'] ?? '';
    $client_so_no   = $_REQUEST['client_so_no'] ?? '';
    $so_pf                = replace_improper_amount($_REQUEST['so_pf'] ?? '');    
    $so_pf_cgst           = replace_improper_amount($_REQUEST['so_pf_cgst'] ?? '');    
    $so_pf_sgst           = replace_improper_amount($_REQUEST['so_pf_sgst'] ?? '');    
    $so_pf_igst           = replace_improper_amount($_REQUEST['so_pf_igst'] ?? '');    
    $so_freight           = replace_improper_amount($_REQUEST['so_freight'] ?? '');   
    $so_freight_cgst      = replace_improper_amount($_REQUEST['so_freight_cgst'] ?? '');   
    $so_freight_sgst      = replace_improper_amount($_REQUEST['so_freight_sgst'] ?? '');   
    $so_freight_igst      = replace_improper_amount($_REQUEST['so_freight_igst'] ?? '');   

    $tax            = array("cgst"=>'0', "sgst"=>'0', "igst"=>'0');

    $array          = $_REQUEST['sales_order'] ?? [];
    $l              = sizeof($array);

    $items=array('product'=>array(),'desc'=>array(),'long_desc'=>array(),'group'=>array(),'quantity'=>array(),'received'=>array(),'unit'=>array(),'price'=>array(),'discount'=>array(),'hsn'=>array(),'tax'=>array());

    $group=0;

    for($i=0;$i<$l;$i++){
        if($array[$i]['so_product_name'] != '' && $array[$i]['so_qty'] != ''){
            
            $items['product'][]     = replace_improper($array[$i]['so_product_name']);
            $items['desc'][]        = replace_improper($array[$i]['so_product_description']);
            $items['long_desc'][]   = replace_improper_textarea($array[$i]['so_product_add_description']);
            $items['group'][]       = $array[$i]['so_display_make'][0] ?? '';
            $items['quantity'][]    = replace_improper($array[$i]['so_qty']);
            $items['received'][]    = '0';
            $items['unit'][]        = replace_improper($array[$i]['so_unit']);
            $items['price'][]       = replace_improper($array[$i]['so_rate']);
            $items['discount'][]    = replace_improper($array[$i]['so_dsc']);
            $items['hsn'][]         = replace_improper($array[$i]['so_hsn']);
            $items['tax'][]         = replace_improper($array[$i]['so_tax']);
            if($state == 'WEST BENGAL'){
                $items['cgst'][]        = $array[$i]['so_cgst'];
                $items['sgst'][]        = $array[$i]['so_sgst'];
                $tax['cgst']            += $array[$i]['so_cgst'];
                $tax['sgst']            += $array[$i]['so_sgst'];
            }else{
                $items['igst'][]        = $array[$i]['so_igst'];
                $tax['igst']            +=$array[$i]['so_igst'];
            }
        }
    }
    $item=json_encode($items);

    $status=0;
    $addons = array('freight'=>array('value'=>$so_freight,'cgst'=>'','sgst'=>'','igst'=>''),'pf'=>array('value'=>$so_pf,'cgst'=>'','sgst'=>'','igst'=>''),'roundoff'=>'');

    if($state == 'WEST BENGAL'){

        $addons['freight']['cgst'] = $so_freight_cgst;
        $addons['freight']['sgst'] = $so_freight_sgst;
        $tax['cgst'] += $so_freight_cgst;
        $tax['sgst'] += $so_freight_sgst;

        $addons['pf']['cgst'] = $so_pf_cgst;
        $addons['pf']['sgst'] = $so_pf_sgst;
        $tax['cgst'] += $so_pf_cgst;
        $tax['sgst'] += $so_pf_sgst;

    }else{

        $addons['freight']['igst'] = $so_freight_igst;
        $tax['igst'] += $so_freight_igst;

        $addons['pf']['igst'] = $so_pf_igst;
        $tax['igst'] += $so_pf_igst;

    }

    $tot_amount = replace_improper_amount($_REQUEST['so_total_final'] ?? '');
    $addons['roundoff'] = replace_improper_amount($_REQUEST['so_round'] ?? '');

    $addon      = json_encode($addons);

    $quotations=array();
    $e_len = sizeof($q_no);
    for($i=0;$i<$e_len;$i++){
        $quotations[] = $q_no[$i];
    }
    $quotation=json_encode($quotations);
    $tax_json   = json_encode($tax);

    if($so_id == '')
    {
        $sql_counter = "SELECT * FROM counter WHERE `key` = 'sales_order'";
        $query_counter = $db->query($sql_counter);
        $row_counter = ($query_counter && $query_counter->num_rows > 0) ? $query_counter->fetch_assoc() : null;
        $row_counter_arr = ($row_counter && isset($row_counter['value'])) ? json_decode($row_counter['value'], true) : null;

        if(is_array($row_counter_arr) && isset($row_counter_arr['prefix'][0]) && isset($row_counter_arr['number'][0]) && isset($row_counter_arr['postfix'][0])){
            $order_no = $row_counter_arr['prefix'][0].str_pad($row_counter_arr['number'][0],3,'0', STR_PAD_LEFT).$row_counter_arr['postfix'][0];
            $row_counter_arr['number'][0] = $row_counter_arr['number'][0] + 1;
        }

        $sql = "INSERT INTO sales_order (`client_name`,`mobile`,`so_no`,`so_date`,`q_no`,`client_so_no`,`items`,`addons`,`collected`,`total`,`tax`,`status`,`log_user`,`log_date`) VALUES ('$client','$mobile','$order_no', '$order_date','$quotation','$client_so_no','$item','$addon','$collected','$tot_amount','$tax_json','$status','$log_user','$log_date')";
        $query = $db->query($sql);
        //echo $sql;

        if($query===true)
        {
            if(is_array($row_counter_arr) && isset($row_counter_arr['prefix'][0])){
                $counter_array = json_encode($row_counter_arr);
                $sql_counter = "UPDATE counter SET `value` = '$counter_array' WHERE `key` = 'sales_order'";
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

        }
    }
    else
    {
        $sql = "UPDATE sales_order SET `client_name` = '$client',`mobile`='$mobile', `so_no`='$order_no',`so_date`='$order_date', `q_no`='$quotation',`client_so_no`='$client_so_no',`items`='$item',`addons`='$addon',`collected`='$collected', `total` = '$tot_amount', `tax` = '$tax_json',`status`='$status',`log_user`='$log_user',`log_date`='$log_date' WHERE `id`='$so_id'";
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

        }
    }

    echo json_encode($validator);
?>
