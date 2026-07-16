<?php
    include ("../connect.php");
    include ("../php_replace_improper.php");

    session_start();

    $id         = $_REQUEST['edit_si_id'];
    $log_user   = $_SESSION['username'];
    $log_date   = date('Y-m-d', strtotime("today"));
    $validator  = array("success"=>true, "messages"=>"There was some error saving the records","si"=>"");

    $array      = $_REQUEST['sales_invoice'];
    $l          = sizeof($array);

    $client         = replace_improper($_REQUEST['si_client']);
    $order_no       = replace_improper($_REQUEST['sales_invoice_no']);
    $invoice_date   = date('Y-m-d', strtotime($_REQUEST['sales_invoice_date']));
    $series         = $_REQUEST['si_series'];

    $address                = array('name'=>'','address_1'=>'','address_2'=>'','city'=>'','pincode'=>'','country'=>'');
    $address['name']        = replace_improper_same($_REQUEST['shipping_name']);
    $address['address_1']   = replace_improper_same($_REQUEST['shipping_add_1']);
    $address['address_2']   = replace_improper_same($_REQUEST['shipping_add_2']);
    $address['city']        = replace_improper_same($_REQUEST['shipping_city']);
    $address['pincode']     = replace_improper_same($_REQUEST['shipping_pincode']);
    $address['country']     = replace_improper_same($_REQUEST['shipping_country']);
    $address                = json_encode($address);
    $ship_state                  = $_REQUEST['shipping_state'];

    $sql_client = "SELECT* FROM clients WHERE `name` = '$client'";
    $query_client = $db->query($sql_client);
    $row_client = $query_client->fetch_assoc();

    $state = $row_client['state'];

    $so_no      = $_REQUEST['si_sales_order'];
    $sales_order= array();
    $e_len      = sizeof($so_no);
    for($i=0;$i<$e_len;$i++){
        $sales_order[] = $so_no[$i];
    }
    $sales_o=json_encode($sales_order);
    
    $q_no       = $_REQUEST['si_quotation[]'];
    $quotations = array();
    $e_len      = sizeof($q_no);
    for($i=0;$i<$e_len;$i++){
        $quotations[] = $q_no[$i];
    }
    $quotation  = json_encode($quotations);
    $tot_amount      = 0;

    $items=array('product'=>array(),'desc'=>array(),'long_desc'=>array(),'group'=>array(),'quantity'=>array(),'unit'=>array(),'price'=>array(),'discount'=>array(),'hsn'=>array(),'tax'=>array());

    $tax            = array("cgst"=>'0', "sgst"=>'0', "igst"=>'0');

    for($i=0;$i<$l;$i++){
        if($array[$i]['si_product_name'] != '' && $array[$i]['si_qty'] != ''){

            if($series == 'PRIMARY')
            {
                $cgst=0;
                $sgst=0;
                $igst=0;

                $total_temp = ($array[$i]['si_rate'] * $array[$i]['si_qty']) - ($array[$i]['si_rate'] * $array[$i]['si_qty'] * $array[$i]['si_dsc'] / 100 );

                if($state == 'WEST BENGAL'){

                    $taxper = $array[$i]['si_tax']/2;
                    $cgst = $total_temp * $taxper / 100;
                    $sgst = $total_temp * $taxper / 100;
                    $cgst = round($cgst*100)/100;
                    $sgst = round($sgst*100)/100;
                }
                else{
                    $taxper = $array[$i]['si_tax'];
                    $igst = $total_temp * $taxper / 100;
                    $igst = round($igst*100)/100;
                }

                $adjustment             = $array[$i]['si_adjustment'];

                $items['product'][]     = replace_improper($array[$i]['si_product_name']);
                $items['desc'][]        = replace_improper_same($array[$i]['si_product_description']);
                $items['long_desc'][]   = replace_improper_textarea($array[$i]['si_product_add_description']);
                $items['group'][]       = $array[$i]['si_display_make'];
                $items['quantity'][]    = replace_improper($array[$i]['si_qty']);
                $items['unit'][]        = replace_improper($array[$i]['si_unit']);
                $items['price'][]       = replace_improper($array[$i]['si_rate']);
                $items['discount'][]    = replace_improper($array[$i]['si_dsc']);
                $items['hsn'][]         = replace_improper($array[$i]['si_hsn']);
                $items['tax'][]         = replace_improper($array[$i]['si_tax']);
                if($state == 'WEST BENGAL'){
                    $items['cgst'][]        = $cgst;
                    $items['sgst'][]        = $sgst;
                    $tax['cgst']            += $cgst;
                    $tax['sgst']            += $sgst;
                }else{
                    $items['igst'][]        = $igst;
                    $tax['igst']            +=$igst;
                }
                $total = $array[$i]['si_qty'] * $array[$i]['si_rate'];

                if ($array[$i]['si_dsc'] != '') {
                    $total = $total - ($array[$i]['si_qty'] * $array[$i]['si_rate']) * ($array[$i]['si_dsc'] / 100);
                }

                $tot_amount += $total + $cgst + $sgst + $igst;  

                if($adjustment == '1'){
                    $ad_product     = $array[$i]['si_product_name'];
                    $ad_qty         = $array[$i]['si_qty'];

                    $ad_product2    = "\"".$ad_product."\"";
                    $sql_adj = "SELECT * FROM sales_invoice WHERE JSON_QUERY(`items`, '$.product') LIKE '%$ad_product2%' AND series = 'SECONDARY'";
                    $query_adj = $db->query($sql_adj);
                    while($row_adj = $query_adj->fetch_assoc()){

                        $adj_id = $row_adj['id'];
                        $adj_items = json_decode($row_adj['items'], true);
                        $adj_len = sizeof($adj_items['product']);

                        for($adj_i=0;$adj_i<$adj_len;$adj_i++){
                            if($adj_items['product'][$adj_i] == $ad_product){
                                if($adj_items['effective_quantity'][$adj_i] > $ad_qty){
                                    $adj_items['effective_quantity'][$adj_i] -= $ad_qty;
                                    $adj_qty = 0;
                                    break;
                                }else{
                                    $adj_qty -= $adj_items['effective_quantity'][$adj_i];
                                    $adj_items['effective_quantity'][$adj_i] = 0;
                                }
                            }
                        }

                        $adj_items = json_encode($adj_items);

                        $sql_update = "UPDATE sales_invoice SET `items` = '$adj_items' WHERE `id` = '$adj_id'";
                        $query_update = $db->query($sql_update);

                        if($adj_qty == 0){
                            break;
                        }
                    }
                }
            }
            else if($series == 'ECOMMERCE')
            {

                $cgst=0;
                $sgst=0;
                $igst=0;

                $total_temp = ($array[$i]['si_rate'] * $array[$i]['si_qty']) - ($array[$i]['si_rate'] * $array[$i]['si_qty'] * $array[$i]['si_dsc'] / 100 );

                if($state == 'WEST BENGAL'){

                    $taxper = $array[$i]['si_tax']/2;
                    $cgst = $total_temp * $taxper / 100;
                    $sgst = $total_temp * $taxper / 100;
                    $cgst = round($cgst*100)/100;
                    $sgst = round($sgst*100)/100;
                }
                else{
                    $taxper = $array[$i]['si_tax'];
                    $igst = $total_temp * $taxper / 100;
                    $igst = round($igst*100)/100;
                }


                $items['product'][]     = replace_improper($array[$i]['si_product_name']);
                $items['desc'][]        = replace_improper_same($array[$i]['si_product_description']);
                $items['long_desc'][]   = replace_improper_textarea($array[$i]['si_product_add_description']);
                $items['group'][]       = $array[$i]['si_display_make'];
                $items['quantity'][]    = replace_improper($array[$i]['si_qty']);
                $items['unit'][]        = replace_improper($array[$i]['si_unit']);
                $items['price'][]       = replace_improper($array[$i]['si_rate']);
                $items['discount'][]    = replace_improper($array[$i]['si_dsc']);
                $items['hsn'][]         = replace_improper($array[$i]['si_hsn']);
                $items['tax'][]         = replace_improper($array[$i]['si_tax']);
                if($state == 'WEST BENGAL'){
                    $items['cgst'][]        = $cgst;
                    $items['sgst'][]        = $sgst;
                    $tax['cgst']            += $cgst;
                    $tax['sgst']            += $sgst;
                }else{
                    $items['igst'][]        = $igst;
                    $tax['igst']            +=$igst;
                }
                $total = $array[$i]['si_qty'] * $array[$i]['si_rate'];

                if ($array[$i]['si_dsc'] != '') {
                    $total = $total - ($array[$i]['si_qty'] * $array[$i]['si_rate']) * ($array[$i]['si_dsc'] / 100);
                }

                $tot_amount += $total + $cgst + $sgst + $igst;  
            }
            else{

                $items['product'][]     = replace_improper($array[$i]['si_product_name']);
                $items['desc'][]        = replace_improper_same($array[$i]['si_product_description']);
                $items['long_desc'][]   = replace_improper_textarea($array[$i]['si_product_add_description']);
                $items['group'][]       = $array[$i]['si_display_make'];
                $items['quantity'][]    = replace_improper($array[$i]['si_qty']);
                $items['effective_quantity'][]    = replace_improper($array[$i]['si_qty']);
                $items['unit'][]        = replace_improper($array[$i]['si_unit']);
                $items['price'][]       = replace_improper($array[$i]['si_rate']);
                $items['discount'][]    = replace_improper($array[$i]['si_dsc']);
                $items['hsn'][]         = replace_improper($array[$i]['si_hsn']);
                $items['tax'][]         = '0';
                if($state == 'WEST BENGAL'){
                    $items['cgst'][]        = 0;
                    $items['sgst'][]        = 0;
                    $tax['cgst']            += 0;
                    $tax['sgst']            += 0;
                }else{
                    $items['igst'][]        = 0;
                    $tax['igst']            += 0;
                }

                $total = $array[$i]['si_qty'] * $array[$i]['si_rate'];

                if ($array[$i]['si_dsc'] != '') {
                    $total = $total - ($array[$i]['si_qty'] * $array[$i]['si_rate']) * ($array[$i]['si_dsc'] / 100);
                }

                $tot_amount += $total;  
            }
        }
    }
    $item       = json_encode($items);

    $si_pf      = replace_improper($_REQUEST['si_pf']);    
    $si_freight = replace_improper($_REQUEST['si_freight']);    
   

    $si_pf           = str_replace(',', '', $si_pf);
    $si_freight      = str_replace(',', '', $si_freight);
    

    $addons = array('freight'=>array('value'=>$si_freight,'cgst'=>'','sgst'=>'','igst'=>''),'pf'=>array('value'=>$si_pf,'cgst'=>'','sgst'=>'','igst'=>''),'roundoff'=>'');

    if($series == 'PRIMARY' || $series == 'ECOMMERCE')
    {

        if($state == 'WEST BENGAL'){
            if($si_freight != '0' && $si_freight != '0.00' && $si_freight != ''){
                $tax_value = $si_freight * 9 / 100;
            }
            else{
                $tax_value = 0;
            }

            $addons['freight']['cgst'] = round($tax_value,2);
            $addons['freight']['sgst'] = round($tax_value,2);
            $tax['cgst'] += round($tax_value,2);
            $tax['sgst'] += round($tax_value,2);

            $tot_amount += $si_freight + $tax_value + $tax_value;

            if($si_pf != '0' && $si_pf != '0.00' && $si_pf != ''){
                $tax_value = $si_pf * 9 / 100;
            }
            else{
                $tax_value = 0;
            }
            $addons['pf']['cgst'] = round($tax_value,2);
            $addons['pf']['sgst'] = round($tax_value,2);
            $tax['cgst'] += round($tax_value,2);
            $tax['sgst'] += round($tax_value,2);

            $tot_amount += $si_pf + $tax_value + $tax_value;

        }else{
            if($si_freight != '0' && $si_freight != '0.00' && $si_freight != ''){
                $tax_value = $si_freight * 18 / 100;
            }
            else{
                $tax_value = 0;
            }
            $addons['freight']['igst'] = round($tax_value,2);
            $tax['igst'] += round($tax_value,2);

            $tot_amount += $si_freight + $tax_value;

            if($si_pf != '0' && $si_pf != '0.00' && $si_pf != ''){
                $tax_value = $si_pf * 18 / 100;
            }
            else{
                $tax_value = 0;
            }
            $addons['pf']['igst'] = round($tax_value,2);
            $tax['igst'] += round($tax_value,2);

            $tot_amount += $si_pf + $tax_value;

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

    $invoice_details_arr = array("buyer_order"=>"","order_date"=>"","payment_terms"=>"","delivery_terms"=>"","other_ref"=>"","despatch_medium"=>"","despatch_doc_no"=>"","despatch_date"=>"","despatch_destination"=>"");

    $invoice_details_arr["buyer_order"]         = replace_improper_same($_REQUEST['buyer_order_no']); 
    if($_REQUEST['buyer_order_date'] != '')
        $invoice_details_arr["order_date"]      = date('Y-m-d', strtotime($_REQUEST['buyer_order_date'])); 
    else
        $invoice_details_arr["order_date"]      = '';
    $invoice_details_arr["payment_terms"]       = replace_improper_same($_REQUEST['terms_payment']); 
    $invoice_details_arr["delivery_terms"]      = replace_improper_same($_REQUEST['terms_delivery']); 
    $invoice_details_arr["other_ref"]           = replace_improper_same($_REQUEST['other_ref']); 
    $invoice_details_arr["despatch_medium"]     = replace_improper_same($_REQUEST['despatch_medium']); 
    $invoice_details_arr["despatch_doc_no"]     = replace_improper_same($_REQUEST['despatch_doc_no']); 
    if($_REQUEST['despatch_date'] != '')
        $invoice_details_arr["despatch_date"]   = date('Y-m-d', strtotime($_REQUEST['despatch_date'])); 
    else
        $invoice_details_arr["despatch_date"]   = '';
    $invoice_details_arr["despatch_destination"]= replace_improper_same($_REQUEST['despatch_destination']); 

    $invoice_details = json_encode($invoice_details_arr);

    $status=0;
    
    if($id == '')
    {
        if($series == 'PRIMARY'){
            $sql_counter = "SELECT * FROM counter WHERE `key` = 'sales_invoice'";
            $query_counter = $db->query($sql_counter);
            $row_counter = $query_counter -> fetch_assoc();
            $row_counter_arr = json_decode($row_counter['value'], true);

            $order_no = $row_counter_arr['prefix'][0].str_pad($row_counter_arr['number'][0],4,'0', STR_PAD_LEFT).$row_counter_arr['postfix'][0];
            $row_counter_arr['number'][0] = $row_counter_arr['number'][0] + 1;  
        }
        else if($series == 'ECOMMERCE'){
            $sql_counter = "SELECT * FROM counter WHERE `key` = 'e-commerce'";
            $query_counter = $db->query($sql_counter);
            $row_counter = $query_counter -> fetch_assoc();
            $row_counter_arr = json_decode($row_counter['value'], true);

            $order_no = $row_counter_arr['prefix'][0].str_pad($row_counter_arr['number'][0],4,'0', STR_PAD_LEFT).$row_counter_arr['postfix'][0];
            $row_counter_arr['number'][0] = $row_counter_arr['number'][0] + 1;  
        }
        else{
            $sql_counter = "SELECT * FROM counter WHERE `key` = 'secondary'";
            $query_counter = $db->query($sql_counter);
            $row_counter = $query_counter -> fetch_assoc();
            $row_counter_arr = json_decode($row_counter['value'], true);

            $order_no = $row_counter_arr['prefix'][0].$row_counter_arr['number'][0].$row_counter_arr['postfix'][0];
            $row_counter_arr['number'][0] = $row_counter_arr['number'][0] + 1; 
        }

        $sql = "INSERT INTO sales_invoice (`client_name`,`si_no`,`series`,`si_date`,`so_no`,`shipping`,`state`,`invoice_details`,`items`,`addons`,`hsn_table`,`total`,`tax`,`status`,`log_user`,`log_date`) VALUES ('$client','$order_no','$series', '$invoice_date','$sales_o','$address','$ship_state','$invoice_details','$item','$addon','1','$tot_amount','$tax_json','$status','$log_user','$log_date')";
        $query = $db->query($sql);

        if($query===true)
        {
            if($series == 'PRIMARY'){
                $counter_array = json_encode($row_counter_arr);
                $sql_counter = "UPDATE counter SET `value` = '$counter_array' WHERE `key` = 'sales_invoice'";
                $query_counter = $db->query($sql_counter);
            }
            else if($series == 'ECOMMERCE'){
                $counter_array = json_encode($row_counter_arr);
                $sql_counter = "UPDATE counter SET `value` = '$counter_array' WHERE `key` = 'e-commerce'";
                $query_counter = $db->query($sql_counter);
            }
            else{
                $counter_array = json_encode($row_counter_arr);
                $sql_counter = "UPDATE counter SET `value` = '$counter_array' WHERE `key` = 'secondary'";
                $query_counter = $db->query($sql_counter);
            }

            $validator['success'] = true;
            $validator['messages'] = "Successfully Added";
            $validator['si'] = $order_no;
        }
        else
        {
            $validator['success'] = false;
            $validator['messages'] = "There was some error saving the records";

        }
    }
    else
    {
        $sql = "UPDATE sales_invoice SET `client_name` = '$client', `si_no`='$order_no',`si_date`='$invoice_date', `so_no`='$sales_o',`shipping`='$address',`state`='$ship_state',`invoice_details`='$invoice_details',`items`='$item',`addons`='$addon', `total` = '$tot_amount', `tax` = '$tax_json',`status`='$status',`cancelled`='0',`log_user`='$log_user',`log_date`='$log_date' WHERE `id`='$id'";
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