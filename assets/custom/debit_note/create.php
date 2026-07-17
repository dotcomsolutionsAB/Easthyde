<?php
    include ("../connect.php");
    include ("../php_replace_improper.php");

    session_start();

    $id                     = $_REQUEST['edit_dn_id'] ?? '';

    $log_user               = $_SESSION['username'] ?? '';
    $log_date               = date('Y-m-d', strtotime("today"));

    $validator              = array("success"=>false, "messages"=>"There was some error saving the records","si"=>"");

    $array                  = $_REQUEST['debit_note'] ?? [];
    if (!is_array($array)) { $array = []; }
    $l                      = sizeof($array);

    $supplier                 = replace_improper($_REQUEST['dn_supplier'] ?? '');
    $purchase_invoice          = replace_improper($_REQUEST['dn_pi_no'] ?? '');
    $dn_no                  = replace_improper($_REQUEST['dn_dn_no'] ?? '');
    $dn_date_raw            = $_REQUEST['dn_date'] ?? '';
    $dn_date                = ($dn_date_raw !== '') ? date('Y-m-d', strtotime((string)$dn_date_raw)) : '';

    $dn_pi_date_raw         = $_REQUEST['dn_pi_date'] ?? '';
    $dn_pi_date             = ($dn_pi_date_raw !== '') ? date('Y-m-d', strtotime((string)$dn_pi_date_raw)) : '';


    $state                  = strtoupper((string)($_REQUEST['dn_state'] ?? ''));

    $tot_amount             = 0;
    $order_no               = '';

    $items=array('product'=>array(),'desc'=>array(),'long_desc'=>array(),'group'=>array(),'quantity'=>array(),'unit'=>array(),'price'=>array(),'discount'=>array(),'hsn'=>array(),'tax'=>array());

    $tax                    = array("cgst"=>'0', "sgst"=>'0', "igst"=>'0');

    for($i=0;$i<$l;$i++){
        $row = is_array($array[$i] ?? null) ? $array[$i] : [];
        if(($row['dn_product_name'] ?? '') != '' && ($row['dn_qty'] ?? '') != ''){

            $cgst=0;
            $sgst=0;
            $igst=0;

            $qty = (float)($row['dn_qty'] ?? 0);
            $rate = (float)str_replace(',', '', (string)($row['dn_rate'] ?? '0'));
            $dsc = (float)($row['dn_dsc'] ?? 0);
            $tax_pct = (float)($row['dn_tax'] ?? 0);

            $total_temp = ($rate * $qty) - ($rate * $qty * $dsc / 100 );

            if($state == 'WEST BENGAL'){

                $taxper = $tax_pct/2;
                $cgst = $total_temp * $taxper / 100;
                $sgst = $total_temp * $taxper / 100;
                $cgst = round($cgst*100)/100;
                $sgst = round($sgst*100)/100;
            }
            else{
                $taxper = $tax_pct;
                $igst = $total_temp * $taxper / 100;
                $igst = round($igst*100)/100;
            }

            $items['product'][]     = replace_improper($row['dn_product_name'] ?? '');
            $items['desc'][]        = replace_improper_same($row['dn_product_description'] ?? '');
            $items['long_desc'][]   = replace_improper_textarea($row['dn_product_add_description'] ?? '');
            $items['group'][]       = $row['dn_display_make'] ?? '';
            $items['quantity'][]    = replace_improper($row['dn_qty'] ?? '');
            $items['unit'][]        = replace_improper($row['dn_unit'] ?? '');
            $items['price'][]       = replace_improper_amount($row['dn_rate'] ?? '');
            $items['discount'][]    = replace_improper($row['dn_dsc'] ?? '');
            $items['hsn'][]         = replace_improper($row['dn_hsn'] ?? '');
            $items['tax'][]         = replace_improper($row['dn_tax'] ?? '');
            if($state == 'WEST BENGAL'){
                $items['cgst'][]        = $cgst;
                $items['sgst'][]        = $sgst;
                $tax['cgst']            += $cgst;
                $tax['sgst']            += $sgst;
            }else{
                $items['igst'][]        = $igst;
                $tax['igst']            += $igst;
            }
            $total = $qty * $rate;

            if ($dsc != 0) {
                $total = $total - ($qty * $rate) * ($dsc / 100);
            }

            $tot_amount += $total + $cgst + $sgst + $igst;  

        }
    }
    $item       = json_encode($items);

    $dn_pf      = replace_improper_amount($_REQUEST['dn_pf'] ?? '');    
    $dn_freight = replace_improper_amount($_REQUEST['dn_freight'] ?? '');    
   

    $dn_pf           = str_replace(',', '', $dn_pf);
    $dn_freight      = str_replace(',', '', $dn_freight);
    $dn_pf_f         = (float)$dn_pf;
    $dn_freight_f    = (float)$dn_freight;
    

    $addons = array('freight'=>array('value'=>$dn_freight,'cgst'=>'','sgst'=>'','igst'=>''),'pf'=>array('value'=>$dn_pf,'cgst'=>'','sgst'=>'','igst'=>''),'roundoff'=>'');

    if($state == 'WEST BENGAL'){
        if($dn_freight_f != 0){
            $tax_value = $dn_freight_f * 9 / 100;
        }
        else{
            $tax_value = 0;
        }

        $addons['freight']['cgst'] = round($tax_value,2);
        $addons['freight']['sgst'] = round($tax_value,2);
        $tax['cgst'] += round($tax_value,2);
        $tax['sgst'] += round($tax_value,2);

        $tot_amount += $dn_freight_f + $tax_value + $tax_value;

        if($dn_pf_f != 0){
            $tax_value = $dn_pf_f * 9 / 100;
        }
        else{
            $tax_value = 0;
        }
        $addons['pf']['cgst'] = round($tax_value,2);
        $addons['pf']['sgst'] = round($tax_value,2);
        $tax['cgst'] += round($tax_value,2);
        $tax['sgst'] += round($tax_value,2);

        $tot_amount += $dn_pf_f + $tax_value + $tax_value;

    }else{
        if($dn_freight_f != 0){
            $tax_value = $dn_freight_f * 18 / 100;
        }
        else{
            $tax_value = 0;
        }
        $addons['freight']['igst'] = round($tax_value,2);
        $tax['igst'] += round($tax_value,2);

        $tot_amount += $dn_freight_f + $tax_value;

        if($dn_pf_f != 0){
            $tax_value = $dn_pf_f * 18 / 100;
        }
        else{
            $tax_value = 0;
        }
        $addons['pf']['igst'] = round($tax_value,2);
        $tax['igst'] += round($tax_value,2);

        $tot_amount += $dn_pf_f + $tax_value;

    }

    if($tax['cgst'] != '')
        $tax['cgst'] = number_format((float)$tax['cgst'],2, '.', '');
    if($tax['sgst'] != '')
        $tax['sgst'] = number_format((float)$tax['sgst'],2, '.', '');
    if($tax['igst'] != '')
        $tax['igst'] = number_format((float)$tax['igst'],2, '.', '');

    $decimal = floor($tot_amount);
    $fraction = $tot_amount - $decimal;

    if ($fraction >= 0.5) {
        $add_fraction = 1 - $fraction;
        $tot_amount += $add_fraction;
    } else {
        $add_fraction = -1 * $fraction;
        $tot_amount += $add_fraction;
    }
    $tot_amount = TrimTrailingZeroes(number_format((float)$tot_amount,2, '.', ''));

    $addons['roundoff'] = $add_fraction;
    if($addons['roundoff'] != '')
        $addons['roundoff'] = number_format((float)$addons['roundoff'],2, '.', '');

    $addon      = json_encode($addons);
    $tax_json   = json_encode($tax);

    $status=0;
    
    if($id == '')
    {
        
        $sql_counter = "SELECT * FROM counter WHERE `key` = 'debit_note'";
        $query_counter = $db->query($sql_counter);
        if ($query_counter && $query_counter->num_rows > 0) {
        $row_counter = $query_counter->fetch_assoc();
        $row_counter_arr = json_decode($row_counter['value'] ?? '', true);

        if(is_array($row_counter_arr) && isset($row_counter_arr['prefix'][0], $row_counter_arr['number'][0], $row_counter_arr['postfix'][0])){
            $order_no = $row_counter_arr['prefix'][0].str_pad((string)$row_counter_arr['number'][0],4,'0', STR_PAD_LEFT).$row_counter_arr['postfix'][0];
            $row_counter_arr['number'][0] = $row_counter_arr['number'][0] + 1;

        $sql = "INSERT INTO debit_note (`supplier`,`purchase_invoice`,`dn_pi_date`,`dn_no`,`dn_date`,`state`,`items`,`addons`,`total`,`tax`,`status`,`log_user`,`log_date`) VALUES ('$supplier','$purchase_invoice','$dn_pi_date','$order_no', '$dn_date','$state','$item','$addon','$tot_amount','$tax_json','$status','$log_user','$log_date')";
        $query = $db->query($sql);

        if($query===true)
        {
                $counter_array = json_encode($row_counter_arr);
                $sql_counter = "UPDATE counter SET `value` = '$counter_array' WHERE `key` = 'debit_note'";
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
        } else {
            $validator['success'] = false;
            $validator['messages'] = "Debit note counter is not configured correctly.";
        }
        } else {
            $validator['success'] = false;
            $validator['messages'] = "Debit note counter not found.";
        }
    }
    else
    {
        $order_no = $dn_no;
        $sql = "UPDATE debit_note SET `supplier` = '$supplier', `purchase_invoice`='$purchase_invoice',`dn_pi_date`='$dn_pi_date',`dn_no`='$dn_no', `dn_date`='$dn_date',`state`='$state',`items`='$item',`addons`='$addon',`total`='$tot_amount',`tax`='$tax_json',`log_user`='$log_user',`log_date`='$log_date' WHERE `id`='$id'";
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
        return strpos((string)$nbr,'.')!==false ? rtrim(rtrim((string)$nbr,'0'),'.') : (string)$nbr;
    }
?>
