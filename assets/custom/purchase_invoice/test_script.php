<?php
    include ("../connect.php");
    include ("../php_replace_improper.php");

    $request = file_get_contents('php://input');
    $input = json_decode($request);

    $array = json_decode($request, true);

    session_start();

    $validator      = array("success"=>true, "messages"=>"There was some error saving the records","pi"=>"");

    $pi_id          = $input -> edit_pi_id;

    $log_user       = $_SESSION['username'];
    $log_date       = date('Y-m-d', strtotime("today"));

    $order_no          = $input -> purchase_invoice_no;
    $order_date     = date('Y-m-d', strtotime($input -> purchase_invoice_date));
    $supplier       = replace_improper($input -> pi_supplier);
    $po_no          = $input -> pi_purchase_order;

    $pi_pf       = replace_improper($input -> pi_pf);
    $pi_freight       = replace_improper($input -> pi_freight);

    $tot_amount     = 0;

    $sql_pull = "SELECT * FROM suppliers WHERE name = '$supplier'";
    $query_pull = $db->query($sql_pull);
    $row_pull = $query_pull->fetch_assoc();
    $state = $row_pull['state'];

    $pi_pf           = str_replace(",","",$pi_pf);
    $pi_freight      = str_replace(",","",$pi_freight);

    $tax            = array("cgst"=>'', "sgst"=>'', "igst"=>'');

    $items=array('product'=>array(),'desc'=>array(),'long_desc'=>array(),'group'=>array(),'quantity'=>array(),'unit'=>array(),'price'=>array(),'discount'=>array(),'hsn'=>array(),'tax'=>array());

    $group=0;

    $address=array('address1'=>'','address2'=>'','address3'=>'');
    $address['address1']=replace_improper($input -> shipping_add_1);
    $address['address2']=replace_improper($input -> shipping_add_2);
    $address['address3']=replace_improper($input -> shipping_add_3);

    $address=json_encode($address);

    foreach($array[purchase_invoice] as $item){

        if($item['pi_product_name'] != '' && $item['pi_qty'] != ''){

            $cgst=0;
            $sgst=0;
            $igst=0;

            $total_temp = ($item['pi_rate'] * $item['pi_qty']) - ($item['pi_rate'] * $item['pi_qty'] * $item['pi_dsc'] / 100 );

            if($state == 'WEST BENGAL'){

                $taxper = $item['pi_tax']/2;
                $cgst = $total_temp * $taxper / 100;
                $sgst = $total_temp * $taxper / 100;
                $cgst = round($cgst*100)/100;
                $sgst = round($sgst*100)/100;
            }
            else{

                $taxper = $item['pi_tax'];
                $igst = $total_temp * $taxper / 100;
                $igst = round($igst*100)/100;
            }

            $items['product'][]     = replace_improper($item['pi_product_name']);
            $items['desc'][]        = replace_improper($item['pi_product_description']);
            $items['long_desc'][]   = replace_improper_textarea($item['pi_product_add_description']);
            $items['group'][]       = $item['pi_display_make'];
            $items['quantity'][]    = replace_improper($item['pi_qty']);
            $items['unit'][]        = replace_improper($item['pi_unit']);
            $items['price'][]       = replace_improper($item['pi_rate']);
            $items['discount'][]    = replace_improper($item['pi_dsc']);
            $items['hsn'][]         = replace_improper($item['pi_hsn']);
            $items['tax'][]         = replace_improper($item['pi_tax']);
            if($state == 'WEST BENGAL'){
                $items['cgst'][]        = $cgst;
                $items['sgst'][]        = $sgst;
                $tax['cgst']            += $cgst;
                $tax['sgst']            += $sgst;
            }else{
                $items['igst'][]        = $igst;
                $tax['igst']            +=$igst;
            }
            $total = $item['pi_qty'] * $item['pi_rate'];

            if ($item['pi_dsc'] != '') {
                $total = $total - ($item['pi_qty'] * $item['pi_rate']) * ($item['pi_dsc'] / 100);
            }

            $tot_amount += $total + $cgst + $sgst + $igst;   

        }
    }
    $item=json_encode($items);

    $status=0;
    $addons = array('freight'=>array('value'=>$pi_freight,'cgst'=>'','sgst'=>'','igst'=>''),'pf'=>array('value'=>$pi_pf,'cgst'=>'','sgst'=>'','igst'=>''),'roundoff'=>'');

    if($state == 'WEST BENGAL'){
        if($pi_freight != '0' && $pi_freight != '0.00' && $pi_freight != ''){
            $tax_value = $pi_freight * 9 / 100;
        }
        else{
            $tax_value = 0;
        }

        $addons['freight']['cgst'] = round($tax_value,2);
        $addons['freight']['sgst'] = round($tax_value,2);
        $tax['cgst'] += round($tax_value,2);
        $tax['sgst'] += round($tax_value,2);

        $tot_amount += $pi_freight + $tax_value + $tax_value;

        if($pi_pf != '0' && $pi_pf != '0.00' && $pi_pf != ''){
            $tax_value = $pi_pf * 9 / 100;
        }
        else{
            $tax_value = 0;
        }
        $addons['pf']['cgst'] = round($tax_value,2);
        $addons['pf']['sgst'] = round($tax_value,2);
        $tax['cgst'] += round($tax_value,2);
        $tax['sgst'] += round($tax_value,2);

        $tot_amount += $pi_pf + $tax_value + $tax_value;

    }else{
        if($pi_freight != '0' && $pi_freight != '0.00' && $pi_freight != ''){
            $tax_value = $pi_freight * 18 / 100;
        }
        else{
            $tax_value = 0;
        }
        $addons['freight']['igst'] = round($tax_value,2);
        $tax['igst'] += round($tax_value,2);

        $tot_amount += $pi_freight + $tax_value;

        if($pi_pf != '0' && $pi_pf != '0.00' && $pi_pf != ''){
            $tax_value = $pi_pf * 18 / 100;
        }
        else{
            $tax_value = 0;
        }
        $addons['pf']['igst'] = round($tax_value,2);
        $tax['igst'] += round($tax_value,2);

        $tot_amount += $pi_pf + $tax_value;

    }

    $tot_amount=$tot_amount + $input -> pi_round;

    $decimal = floor($tot_amount);
    $fraction = $tot_amount - $decimal;

    // if ($fraction >= 0.5) {
    //     $add_fraction = 1 - $fraction;
    //     $tot_amount += $add_fraction;
    // } else {
    //     $add_fraction = -1 * $fraction;
    //     $tot_amount += $add_fraction;
    // }

    $addons['roundoff'] = $input -> pi_round;

    $addon      = json_encode($addons);
    $tax_json   = json_encode($tax);

    $purchase_order=array();
    $e_len = sizeof($po_no);
    for($i=0;$i<$e_len;$i++){
        $purchase_order[] = $po_no[$i];
    }
    $purchase_o=json_encode($purchase_order);

    if($pi_id == '')
    {

        $sql = "INSERT INTO purchase_invoice (`supplier_name`,`pi_no`,`pi_date`,`po_no`,`shipping`,`items`,`addons`,`total`,`tax`,`status`,`log_user`,`log_date`) VALUES ('$supplier','$order_no', '$order_date','$purchase_o','$address','$item','$addon','$tot_amount','$tax_json','$status','$log_user','$log_date')";
        $query = $db->query($sql);

        if($query===true)
        {
            $counter_array = json_encode($row_counter_arr);
            $sql_counter = "UPDATE counter SET `value` = '$counter_array' WHERE `key` = 'purchase_invoice'";
            $query_counter = $db->query($sql_counter);

            $validator['success'] = true;
            $validator['messages'] = "Successfully Added";
            $validator['pi'] = $order_no;
        }
        else
        {
            $validator['success'] = false;
            $validator['messages'] = "There was some error saving the records";

        }
    }
    else{
        $sql = "UPDATE purchase_invoice SET `supplier_name` = '$supplier', `pi_no`='$order_no',`pi_date`='$order_date', `po_no`='$purchase_o',`shipping`='$address',`items`='$item',`addons`='$addon', `total` = '$tot_amount', `tax` = '$tax_json',`status`='$status',`log_user`='$log_user',`log_date`='$log_date' WHERE `id`='$pi_id'";
        $query = $db->query($sql);

        if($query===true)
        {
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
?>