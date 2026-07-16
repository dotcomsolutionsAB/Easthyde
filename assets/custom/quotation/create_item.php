<?php
    include ("../connect.php");
    include ("../php_replace_improper.php");

    session_start();

    $validator = array("success"=>true, "messages"=>"There was some error saving the records");

    $q_id = $_REQUEST['ai_q_id'] ?? '';
    $product = replace_improper($_REQUEST['ai_q_product'] ?? '');
    $description = replace_improper(trim((string)($_REQUEST['ai_q_description'] ?? '')));
    $long_description = $_REQUEST['ai_q_product_add_description'] ?? '';
    $quantity = replace_improper($_REQUEST['ai_q_quantity'] ?? '');
    $unit = replace_improper($_REQUEST['ai_q_unit'] ?? '');
    $price = replace_improper($_REQUEST['ai_q_price'] ?? '');
    $discount = replace_improper($_REQUEST['ai_q_dsc'] ?? '');
    $hsn = replace_improper($_REQUEST['ai_q_hsn'] ?? '');
    $tax = replace_improper($_REQUEST['ai_q_tax'] ?? '');

    $group=0;

    $s = trim((string)$long_description);
    $s=str_replace("\"","",$s);
    $s=str_replace("'","",$s);
    $add_description = str_replace(array("\r\n","\r","\n"),'|',$s);

    $amount = ($quantity * $price) * (100-$discount) / 100;
    $tax_amount = $amount * $tax / 100;

    $sql = "SELECT * FROM quotation WHERE `id` = '$q_id'";
    $query = $db->query($sql);
    $row = ($query && ($tmp = $query->fetch_assoc())) ? $tmp : null;
    if (!$row) {
        $validator['success'] = false;
        $validator['messages'] = "Record not found";
        echo json_encode($validator);
        exit;
    }

    $items = json_decode($row['items'] ?? '', true);
    if (!is_array($items)) { $items = ['product'=>[], 'group'=>[], 'quantity'=>[], 'unit'=>[], 'price'=>[], 'discount'=>[], 'hsn'=>[], 'tax'=>[], 'desc'=>[], 'long_desc'=>[], 'tax_amount'=>[], 'amount'=>[]]; }

    if($product != '' && $quantity != ''){
        $sql_temp = "SELECT * FROM product WHERE name = '$product'";
        $query_temp = $db->query($sql_temp);
        $row_temp = ($query_temp && ($tmp = $query_temp->fetch_assoc())) ? $tmp : null;

        $group = $row_temp['default_make'] ?? 0;

        $items['product'][] = $product;
        $items['group'][] = $group;
        $items['quantity'][] = $quantity;
        $items['unit'][] = $unit;
        $items['price'][] = $price;
        $items['discount'][] = $discount;
        $items['hsn'][] = $hsn;
        $items['tax'][] = $tax;
        $items['desc'][] = $description;
        $items['long_desc'][] = $add_description;
        $items['tax_amount'][] = $tax_amount;
        $items['amount'][] = $amount;
    }

    $item=json_encode($items);

    $status=0;

    $sql_update = "UPDATE quotation SET `items` = '$item' WHERE `id` = '$q_id'";
    $query_update = $db->query($sql_update);

    if($query_update === true)
    {
        $validator['success'] = true;
        $validator['messages'] = "Successfully Added";
        // $validator['po'] = $po;
    }
    else
    {
        $validator['success'] = false;
        $validator['messages'] = "There was some error saving the records";

    }

    echo json_encode($validator);
?>
