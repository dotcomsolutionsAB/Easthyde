<?php
    include ("../connect.php");
    include ("../php_replace_improper.php");

    session_start();

    $validator = array("success"=>true, "messages"=>"There was some error saving the records");

    $po_id          = $_REQUEST['edit_po_id'];
    $maintenance    = $_REQUEST['maintenance'];

    $log_user   = $_SESSION['username'];
    $log_date   = date('Y-m-d', strtotime("today"));
    
    $supplier   = replace_improper($_REQUEST['po_supplier']);
    $order_date = date('Y-m-d', strtotime($_REQUEST['purchase_date']));
    $po_pf            = replace_improper_amount($_REQUEST['po_pf']);    
    $po_pf_cgst       = replace_improper($_REQUEST['po_pf_cgst']);    
    $po_pf_sgst       = replace_improper($_REQUEST['po_pf_sgst']);    
    $po_pf_igst       = replace_improper($_REQUEST['po_pf_igst']);    

    $po_freight       = replace_improper_amount($_REQUEST['po_freight']);    
    $po_freight_cgst  = replace_improper($_REQUEST['po_freight_cgst']);    
    $po_freight_sgst  = replace_improper($_REQUEST['po_freight_sgst']);    
    $po_freight_igst  = replace_improper($_REQUEST['po_freight_igst']);    

    $tax            = array("cgst"=>'', "sgst"=>'', "igst"=>'');

    $sql_pull = "SELECT * FROM suppliers WHERE name = '$supplier'";
    $query_pull = $db->query($sql_pull);
    $row_pull = $query_pull->fetch_assoc();
    $state = $row_pull['state'];

    $address                = array('name'=>'','address_1'=>'','address_2'=>'','city'=>'','pincode'=>'','country'=>'');
    $address['name']        = replace_improper_same($_REQUEST['po_shipping_name']);
    $address['address_1']   = replace_improper_same($_REQUEST['po_shipping_add_1']);
    $address['address_2']   = replace_improper_same($_REQUEST['po_shipping_add_2']);
    $address['city']        = replace_improper_same($_REQUEST['po_shipping_city']);
    $address['pincode']     = replace_improper_same($_REQUEST['po_shipping_pincode']);
    $address['country']     = replace_improper_same($_REQUEST['po_shipping_country']);
    $address                = json_encode($address);
    $ship_state                  = $_REQUEST['po_shipping_state'];

    $mode           = replace_improper_same($_REQUEST['po_mode']); 
    $supplier_ref   = replace_improper_same($_REQUEST['po_supplier_ref']); 
    $other_ref      = replace_improper_same($_REQUEST['po_other_ref']); 
    $despatch       = replace_improper_same($_REQUEST['po_despatch']); 
    $destination    = replace_improper_same($_REQUEST['po_destination']); 
    $terms          = replace_improper_same($_REQUEST['po_terms']); 

    $top = array("mode"=>$mode, "supplier_ref"=>$supplier_ref,"other_ref"=>$other_ref,"despatch"=>$despatch,"destination"=>$destination,"terms"=>$terms);
    $top = json_encode($top);
    
    $array      = $_REQUEST['purchase_order'];
    $l          = sizeof($array);

    $items=array('product'=>array(),'desc'=>array(),'long_desc'=>array(),'group'=>array(),'quantity'=>array(),'received'=>array(),'unit'=>array(),'price'=>array(),'discount'=>array(),'hsn'=>array(),'tax'=>array());

    $group=0;

    for($i=0;$i<116;$i++){
        if($array[$i]['po_product_name'] != '' && $array[$i]['po_qty'] != ''){

            $pr = $array[$i]['po_product_name'];

            $items['product'][]     = replace_improper($array[$i]['po_product_name']);
            $items['desc'][]        = replace_improper($array[$i]['po_product_description']);
            $items['long_desc'][]   = replace_improper_textarea($array[$i]['po_product_add_description']);
            $items['group'][]       = $array[$i]['po_display_make'];
            $items['quantity'][]    = replace_improper($array[$i]['po_qty']);
            $items['received'][]    = '0';
            $items['unit'][]        = replace_improper($array[$i]['po_unit']);
            $items['price'][]       = replace_improper($array[$i]['po_rate']);
            $items['discount'][]    = replace_improper($array[$i]['po_dsc']);
            $items['hsn'][]         = replace_improper($array[$i]['po_hsn']);
            $items['tax'][]         = replace_improper($array[$i]['po_tax']);
            if($state == 'WEST BENGAL'){
                $items['cgst'][]        = $array[$i]['po_cgst'];
                $items['sgst'][]        = $array[$i]['po_sgst'];
                $tax['cgst']            += $array[$i]['po_cgst'];
                $tax['sgst']            += $array[$i]['po_sgst'];
            }else{
                $items['igst'][]        = $array[$i]['po_igst'];
                $tax['igst']            +=$array[$i]['po_igst'];
            }             

            $sql_temp = "DELETE FROM purchase_bag WHERE product_name = '$pr'";
            $query_temp = $db->query($sql_temp);

        }
    }
    $item=json_encode($items);

    $status=0;
    $addons = array('freight'=>array('value'=>$po_freight,'cgst'=>'','sgst'=>'','igst'=>''),'pf'=>array('value'=>$po_pf,'cgst'=>'','sgst'=>'','igst'=>''),'roundoff'=>'');

    if($state == 'WEST BENGAL'){

        $addons['freight']['cgst'] = $po_freight_cgst;
        $addons['freight']['sgst'] = $po_freight_sgst;
        $tax['cgst'] += $po_freight_cgst;
        $tax['sgst'] += $po_freight_sgst;

        $addons['pf']['cgst'] = $po_pf_cgst;
        $addons['pf']['sgst'] = $po_pf_sgst;
        $tax['cgst'] += $po_pf_cgst;
        $tax['sgst'] += $po_pf_sgst;

    }else{

        $addons['freight']['igst'] = $po_freight_igst;
        $tax['igst'] += $po_freight_igst;

        $addons['pf']['igst'] = $po_pf_igst;
        $tax['igst'] += $po_pf_igst;

    }

    $tot_amount = replace_improper_amount($_REQUEST['po_total_final']);
    $addons['roundoff'] = replace_improper_amount($_REQUEST['po_round']);

    $addon      = json_encode($addons);
    $tax_json   = json_encode($tax);

    if($po_id == '')
    {        
        $sql_counter = "SELECT * FROM counter WHERE `key` = 'purchase_order'";
        $query_counter = $db->query($sql_counter);
        $row_counter = $query_counter -> fetch_assoc();
        $row_counter_arr = json_decode($row_counter['value'], true);

        $order_no = $row_counter_arr['prefix'][0].str_pad($row_counter_arr['number'][0],3,'0', STR_PAD_LEFT).$row_counter_arr['postfix'][0];
        $row_counter_arr['number'][0] = $row_counter_arr['number'][0] + 1;

        $sql = "INSERT INTO purchase_order (`supplier_name`,`po_no`,`po_date`,`top`,`items`,`addons`,`shipping`,`state`,`total`,`tax`,`status`,`log_user`,`log_date`) VALUES ('$supplier','$order_no', '$order_date', '$top','$item','$addon','$address','$ship_state','$tot_amount','$tax_json','$status','$log_user','$log_date')";
        $query = $db->query($sql);
        

        if($query===true)
        {
            if($maintenance != 1){
                $counter_array = json_encode($row_counter_arr);
                $sql_counter = "UPDATE counter SET `value` = '$counter_array' WHERE `key` = 'purchase_order'";
                $query_counter = $db->query($sql_counter);
            }

            $validator['success'] = true;
            $validator['messages'] = "Successfully Added";
            $validator['messages'] = $l;
            $validator['po'] = $order_no;
        }
        else
        {
            $validator['success'] = false;
            $validator['messages'] = "There was some error saving the records";
        }
    }
    else{
        $sql = "UPDATE purchase_order SET `supplier_name` = '$supplier',`po_date`='$order_date',`top`='$top',`items`='$item',`addons`='$addon',`shipping`='$address',`state`='$ship_state', `total` = '$tot_amount', `tax` = '$tax_json',`status`='$status',`log_user`='$log_user',`log_date`='$log_date' WHERE `id`='$po_id'";
        $query = $db->query($sql);

        if($query===true)
        {
            $validator['success'] = true;
            $validator['messages'] = "Successfully Updated";
            $validator['po'] = $order_no;
        }
        else
        {
            $validator['success'] = false;
            $validator['messages'] = "There was some error saving the records";

        }

    }

    $sql_update = "UPDATE purchase_bag SET temp = '0' WHERE 1";
    $query_update = $db->query($sql_update);

    echo json_encode($validator);
?>