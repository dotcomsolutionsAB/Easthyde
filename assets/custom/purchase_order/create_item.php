<?php
    include ("../connect.php");
    include ("../php_replace_improper.php");

    session_start();

    $validator = array("success"=>false, "messages"=>"There was some error saving the records", 'po'=>'');

    $po_id = $_REQUEST['ai_po_id'] ?? '';
    $product = replace_improper($_REQUEST['ai_po_product'] ?? '');
    $description = replace_improper(trim((string)($_REQUEST['ai_po_description'] ?? '')));
    $long_description = $_REQUEST['ai_po_product_add_description'] ?? '';
    $quantity = replace_improper($_REQUEST['ai_po_quantity'] ?? '');
    $unit = replace_improper($_REQUEST['ai_po_unit'] ?? '');
    $price = replace_improper_amount($_REQUEST['ai_po_price'] ?? '');
    $discount = replace_improper($_REQUEST['ai_po_dsc'] ?? '');
    $hsn = replace_improper($_REQUEST['ai_po_hsn'] ?? '');
    $tax = replace_improper($_REQUEST['ai_po_tax'] ?? '');

    $group=0;

    $s = trim((string)$long_description);
    $s=str_replace("\"","",$s);
    $s=str_replace("'","",$s);
    $add_description = str_replace(array("\r\n","\r","\n"),'|',$s);

    $amount = ((float)$quantity * (float)$price) * (100 - (float)$discount) / 100;
    $tax_amount = $amount * (float)$tax / 100;

    $sql = "SELECT * FROM purchase_order WHERE `po_no` = '$po_id'";
    $query = $db->query($sql);
    $row = ($query && ($tmp = $query->fetch_assoc())) ? $tmp : null;
    if (!$row) {
        $validator['success'] = false;
        $validator['messages'] = "Record not found";
        echo json_encode($validator);
        exit;
    }

    $po = $row['po_no'];

    $items = json_decode($row['items'] ?? '', true);
    if (!is_array($items)) { $items = ['product'=>[], 'group'=>[], 'quantity'=>[], 'received'=>[], 'unit'=>[], 'price'=>[], 'discount'=>[], 'hsn'=>[], 'tax'=>[], 'desc'=>[], 'long_desc'=>[], 'tax_amount'=>[], 'amount'=>[]]; }

    if($product != '' && $quantity != ''){
        $sql_temp = "SELECT * FROM product WHERE name = '$product'";
        $query_temp = $db->query($sql_temp);
        $row_temp = ($query_temp && ($tmp = $query_temp->fetch_assoc())) ? $tmp : null;

        $group = $row_temp['default_make'] ?? 0;

        $items['product'][] = $product;
        $items['group'][] = $group;
        $items['quantity'][] = $quantity;
        $items['received'][] = '0';
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

    $sql_update = "UPDATE purchase_order SET `items` = '$item' WHERE `po_no` = '$po_id'";
    $query_update = $db->query($sql_update);

    if($query_update === true)
    {
        $validator['success'] = true;
        $validator['messages'] = "Successfully Added";
        $validator['po'] = $po;
    }
    else
    {
        $validator['success'] = false;
        $validator['messages'] = "There was some error saving the records";

    }

    echo json_encode($validator);
?>
