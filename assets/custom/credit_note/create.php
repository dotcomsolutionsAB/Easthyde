<?php
    include ("../connect.php");
    include ("../php_replace_improper.php");

    session_start();

    $id                     = $_REQUEST['edit_cn_id'];

    $log_user               = $_SESSION['username'];
    $log_date               = date('Y-m-d', strtotime("today"));

    $validator              = array("success"=>true, "messages"=>"There was some error saving the records","si"=>"");

    $array                  = $_REQUEST['credit_note'];
    $l                      = sizeof($array);

    $client                 = replace_improper($_REQUEST['cn_client']);
    $sales_invoice          = replace_improper($_REQUEST['cn_si_no']);
    $cn_no                  = replace_improper($_REQUEST['cn_cn_no']);
    $cn_date                = date('Y-m-d', strtotime($_REQUEST['cn_date']));

    $state                  = $_REQUEST['cn_state'];

    $tot_amount             = 0;

    $items=array('product'=>array(),'quantity'=>array(),'unit'=>array(),'price'=>array(),'discount'=>array(),'hsn'=>array(),'tax'=>array(),'desc'=>array(),'tax_amount'=>array(),'amount'=>array(),'place'=>array(),'profit'=>array());

    $tax            = array("cgst"=>'0', "sgst"=>'0', "igst"=>'0');

    for($i=0;$i<$l;$i++){
        if($array[$i]['cn_product_name'] != '' && $array[$i]['cn_qty'] != ''){

            $cgst=0;
            $sgst=0;
            $igst=0;

            $total_temp = ($array[$i]['cn_rate'] * $array[$i]['cn_qty']) - ($array[$i]['cn_rate'] * $array[$i]['cn_qty'] * $array[$i]['cn_dsc'] / 100 );

            if($state == 'WEST BENGAL'){

                $taxper = $array[$i]['cn_tax']/2;
                $cgst = $total_temp * $taxper / 100;
                $sgst = $total_temp * $taxper / 100;
                $cgst = (float)number_format($cgst,2, '.', '');
                $sgst = (float)number_format($sgst,2, '.', '');
            }
            else{
                $taxper = $array[$i]['cn_tax'];
                $igst = $total_temp * $taxper / 100;
                $igst = (float)number_format($igst,2, '.', '');
            }

            $ppr = replace_improper($array[$i]['cn_product_name']).',';
            if (strpos($products_array, $ppr) == false)
                $products_array .= $ppr.',';

            $items['product'][]     = replace_improper($array[$i]['cn_product_name']);
            $items['desc'][]        = replace_improper_textarea($array[$i]['cn_product_add_description']);
            $items['quantity'][]    = replace_improper($array[$i]['cn_qty']);
            $items['unit'][]        = replace_improper($array[$i]['cn_unit']);
            $items['price'][]       = replace_improper($array[$i]['cn_rate']);
            $items['discount'][]    = replace_improper($array[$i]['cn_dsc']);
            $items['hsn'][]         = replace_improper($array[$i]['cn_hsn']);
            $items['tax'][]         = replace_improper($array[$i]['cn_tax']);
            $items['place'][]       = replace_improper($array[$i]['cn_place']);
            $items['profit'][]      = "0";
            if($state == 'WEST BENGAL'){
                $items['cgst'][]        = $cgst;
                $items['sgst'][]        = $sgst;
                $tax['cgst']            += $cgst;
                $tax['sgst']            += $sgst;
            }else{
                $items['igst'][]        = $igst;
                $tax['igst']            +=$igst;
            }
            $total = $array[$i]['cn_qty'] * $array[$i]['cn_rate'];

            if ($array[$i]['cn_dsc'] != '') {
                $total = $total - ($array[$i]['cn_qty'] * $array[$i]['cn_rate']) * ($array[$i]['cn_dsc'] / 100);
            }

            $tot_amount += $total + $cgst + $sgst + $igst;  
        }
    }
    $item       = json_encode($items);

    $cn_pf      = replace_improper($_REQUEST['cn_pf']);    
    $cn_freight = replace_improper($_REQUEST['cn_freight']);    
   

    $cn_pf           = str_replace(',', '', $cn_pf);
    $cn_freight      = str_replace(',', '', $cn_freight);
    

    $addons = array('freight'=>array('value'=>$cn_freight,'cgst'=>'','sgst'=>'','igst'=>''),'pf'=>array('value'=>$cn_pf,'cgst'=>'','sgst'=>'','igst'=>''),'roundoff'=>'');

    if($state == 'WEST BENGAL'){
        if($cn_freight != '0' && $cn_freight != '0.00' && $cn_freight != ''){
            $tax_value = $cn_freight * 9 / 100;
        }
        else{
            $tax_value = 0;
        }

        $addons['freight']['cgst'] = round($tax_value,2);
        $addons['freight']['sgst'] = round($tax_value,2);
        $tax['cgst'] += round($tax_value,2);
        $tax['sgst'] += round($tax_value,2);

        $tot_amount += $cn_freight + $tax_value + $tax_value;

        if($cn_pf != '0' && $cn_pf != '0.00' && $cn_pf != ''){
            $tax_value = $cn_pf * 9 / 100;
        }
        else{
            $tax_value = 0;
        }
        $addons['pf']['cgst'] = round($tax_value,2);
        $addons['pf']['sgst'] = round($tax_value,2);
        $tax['cgst'] += round($tax_value,2);
        $tax['sgst'] += round($tax_value,2);

        $tot_amount += $cn_pf + $tax_value + $tax_value;

    }else{
        if($cn_freight != '0' && $cn_freight != '0.00' && $cn_freight != ''){
            $tax_value = $cn_freight * 18 / 100;
        }
        else{
            $tax_value = 0;
        }
        $addons['freight']['igst'] = round($tax_value,2);
        $tax['igst'] += round($tax_value,2);

        $tot_amount += $cn_freight + $tax_value;

        if($cn_pf != '0' && $cn_pf != '0.00' && $cn_pf != ''){
            $tax_value = $cn_pf * 18 / 100;
        }
        else{
            $tax_value = 0;
        }
        $addons['pf']['igst'] = round($tax_value,2);
        $tax['igst'] += round($tax_value,2);

        $tot_amount += $cn_pf + $tax_value;

    }

    if($tax['cgst'] != '')
        $tax['cgst'] = number_format($tax['cgst'],2, '.', '');
    if($tax['sgst'] != '')
        $tax['sgst'] = number_format($tax['sgst'],2, '.', '');
    if($tax['igst'] != '')
        $tax['igst'] = number_format($tax['igst'],2, '.', '');

    $decimal = floor($tot_amount);
    $fraction = $tot_amount - $decimal;

    if ($fraction >= 0.5) {
        $add_fraction = 1 - $fraction;
        $tot_amount += $add_fraction;
    } else {
        $add_fraction = -1 * $fraction;
        $tot_amount += $add_fraction;
    }
    $tot_amount = TrimTrailingZeroes(number_format($tot_amount,2, '.', ''));

    $addons['roundoff'] = $add_fraction;
    if($addons['roundoff'] != '')
        $addons['roundoff'] = number_format($addons['roundoff'],2, '.', '');

    $addon      = json_encode($addons);
    $tax_json   = json_encode($tax);

    $status=0;
    
    if($id == '')
    {
        
        $sql_counter = "SELECT * FROM counter WHERE `key` = 'credit_note'";
        $query_counter = $db->query($sql_counter);
        $row_counter = $query_counter -> fetch_assoc();
        $row_counter_arr = json_decode($row_counter['value'], true);

        $order_no = $row_counter_arr['prefix'][0].str_pad($row_counter_arr['number'][0],4,'0', STR_PAD_LEFT).$row_counter_arr['postfix'][0];
        $row_counter_arr['number'][0] = $row_counter_arr['number'][0] + 1;  

        $sql = "INSERT INTO credit_note (`client`,`sales_invoice`,`cn_no`,`cn_date`,`state`,`items`,`addons`,`total`,`tax`,`status`,`log_user`,`log_date`) VALUES ('$client','$sales_invoice','$cn_no', '$cn_date','$state','$item','$addon','$tot_amount','$tax_json','$status','$log_user','$log_date')";
        $query = $db->query($sql);

        if($query===true)
        {
           
            $counter_array = json_encode($row_counter_arr);
            $sql_counter = "UPDATE counter SET `value` = '$counter_array' WHERE `key` = 'credit_note'";
            $query_counter = $db->query($sql_counter);
            
            $validator['success'] = true;
            $validator['messages'] = "Successfully Added";
            $validator['cn'] = $order_no;
        }
        else
        {
            $validator['success'] = false;
            $validator['messages'] = "There was some error saving the records";

        }
    }
    else
    {
        $sql = "UPDATE credit_note SET `client` = '$client', `sales_invoice`='$sales_invoice',`cn_no`='$cn_no', `cn_date`='$cn_date',`state`='$state',`items`='$item',`addons`='$addon',`total`='$tot_amount',`tax`='$tax_json',`log_user`='$log_user',`log_date`='$log_date' WHERE `id`='$id'";
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

    function TrimTrailingZeroes($nbr) {
        return strpos($nbr,'.')!==false ? rtrim(rtrim($nbr,'0'),'.') : $nbr;
    }
?>