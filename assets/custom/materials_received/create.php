<?php
    include ("../connect.php");
    include ("../php_replace_improper.php");
    include ("../fy_access.php");

    session_start();

    function generate_consignment_voucher_no($db, $counter_key, $fallback_prefix){
        $sql_counter = "SELECT * FROM counter WHERE `key` = '$counter_key'";
        $query_counter = $db->query($sql_counter);
        if($query_counter && $query_counter->num_rows > 0){
            $row_counter = $query_counter->fetch_assoc();
            $row_counter_arr = json_decode($row_counter['value'] ?? '', true);
            if(is_array($row_counter_arr) && isset($row_counter_arr['prefix'][0]) && isset($row_counter_arr['number'][0]) && isset($row_counter_arr['postfix'][0])){
                $voucher_no = $row_counter_arr['prefix'][0] . str_pad($row_counter_arr['number'][0], 4, '0', STR_PAD_LEFT) . $row_counter_arr['postfix'][0];
                $row_counter_arr['number'][0] = $row_counter_arr['number'][0] + 1;
                $counter_array = json_encode($row_counter_arr);
                $sql_counter_update = "UPDATE counter SET `value` = '$counter_array' WHERE `key` = '$counter_key'";
                $db->query($sql_counter_update);
                return $voucher_no;
            }
        }
        $counter_value = json_encode(array("prefix"=>array($fallback_prefix.date('y')."-"),"number"=>array(2),"postfix"=>array("")));
        $db->query("INSERT INTO counter (`key`,`value`) VALUES ('$counter_key','$counter_value')");
        return $fallback_prefix . date('y') . "-0001";
    }

    $array = $_REQUEST['materials_received'] ?? [];
    if (!is_array($array)) { $array = []; }
    $l = sizeof($array);

    $id = $_REQUEST['mr_edit_id'] ?? '';

    $supplier_name = replace_improper($_REQUEST['mr_supplier_name'] ?? '');
    $voucher_type = isset($_REQUEST['mr_voucher_type']) ? strtoupper(replace_improper($_REQUEST['mr_voucher_type'] ?? '')) : 'MRN';
    if($voucher_type !== 'MRN' && $voucher_type !== 'MRTN'){
        $voucher_type = 'MRN';
    }
    $mr_date = $_REQUEST['mr_date'] ?? '';
    $date = ($mr_date !== '') ? date('Y-m-d', strtotime($mr_date)) : '';
    fy_assert_or_exit_json($date, "Material date");

	$log_user = $_SESSION['username'] ?? '';
    $log_date = date('Y-m-d', strtotime("today"));
    $validator = array("success"=>false, "messages"=>"There was some error saving the records");

    $items=array('product'=>array(),'desc'=>array(),'quantity'=>array(),'unit'=>array(),'rate'=>array());

    for($i=0;$i<$l;$i++){
        $row = is_array($array[$i] ?? null) ? $array[$i] : [];
        if(($row['mr_product_name'] ?? '') != '' && ($row['mr_qty'] ?? '') != ''){

        	$pr = $row['mr_product_name'];
            $sql_temp = "SELECT * FROM product WHERE name = '$pr'";
            $query_temp = $db->query($sql_temp);
            $row_temp = ($query_temp && ($tmp = $query_temp->fetch_assoc())) ? $tmp : [];

            $items['product'][] = replace_improper($row['mr_product_name'] ?? '');
            $items['desc'][] = replace_improper($row['mr_desc'] ?? '');
            $items['quantity'][] = replace_improper($row['mr_qty'] ?? '');
            $items['unit'][] = replace_improper($row['mr_unit'] ?? '');
            $items['rate'][] = replace_improper_amount($row['mr_rate'] ?? '');
		}
	}
	$items=json_encode($items);

    $supplier_id = 0;
    $query_supplier = $db->query("SELECT id FROM suppliers WHERE name = '$supplier_name' LIMIT 1");
    if($query_supplier && $query_supplier->num_rows > 0){
        $row_supplier = ($query_supplier) ? $query_supplier->fetch_assoc() : null;
        $supplier_id = (int)($row_supplier['id'] ?? 0);
    }

    if($id=='')
    {
        $counter_key = ($voucher_type == 'MRTN') ? 'materials_return_note' : 'materials_receipt_note';
        $fallback_prefix = ($voucher_type == 'MRTN') ? 'MRTN-' : 'MRN-';
        $voucher_no = generate_consignment_voucher_no($db, $counter_key, $fallback_prefix);
        $sql = "INSERT INTO materials_received (`supplier_id`,`supplier_name`,`voucher_no`,`voucher_type`,`date`,`items`,`log_user`,`log_date`) VALUES ('$supplier_id','$supplier_name','$voucher_no','$voucher_type','$date','$items','$log_user','$log_date')";
        $query = $db->query($sql);

        if($query===true)
        {
            $validator['success'] = true;
            $validator['messages'] = "Successfully Added";
        }
        else
        {
            $validator['success'] = false;
            $validator['messages'] = "There was some error saving the records";

        }
    }
    else
    {
        $sql = "UPDATE materials_received SET `supplier_id`='$supplier_id', `supplier_name` = '$supplier_name', `voucher_type`='$voucher_type', `date`='$date',`items`='$items',`log_user`='$log_user',`log_date`='$log_date' WHERE `id`='$id'";
        $query = $db->query($sql);

        if($query===true)
        {
            $validator['success'] = true;
            $validator['messages'] = "Successfully Updated";
        }
        else
        {
            $validator['success'] = false;
            $validator['messages'] = "There was some error saving the records";

        }
    }

    echo json_encode($validator);
?>