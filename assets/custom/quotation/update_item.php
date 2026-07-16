<?php
    include ("../connect.php");
    include ("../php_replace_improper.php");

    session_start();

    $validator = array("success"=>true, "messages"=>"There was some error saving the records");

    $q_id = $_REQUEST['edit_qi_id'];
    $index = $_REQUEST['edit_q_item_id'];
    $product = replace_improper($_REQUEST['edit_q_product_name']);
    $description = replace_improper($_REQUEST['edit_q_product_description']);
    $long_description = $_REQUEST['edit_q_product_add_description'];
    $quantity = replace_improper($_REQUEST['edit_q_qty']);
    $unit = replace_improper($_REQUEST['edit_q_unit']);
    $unit = replace_improper($_REQUEST['edit_q_unit']);
    $price = replace_improper($_REQUEST['edit_q_rate']);
    $discount = replace_improper($_REQUEST['edit_q_dsc']);
    $hsn = replace_improper($_REQUEST['edit_q_hsn']);
    $tax = replace_improper($_REQUEST['edit_q_tax']);
    $group_ch = $_REQUEST['qt_group'];

    $s = $long_description;
    $s=str_replace("\"","",$s);
    $s=str_replace("'","",$s);
    $add_description = str_replace(array("\r\n","\r","\n"),'|',trim($s));

    $group = 0;

    if($group_ch == "on")
        $group="1";

    $sql = "SELECT * FROM quotation WHERE `quotation_no` = '$q_id'";
    $query = $db->query($sql);
    $row = $query->fetch_assoc();

    $items = json_decode($row['items'], true);

    $amount = ($quantity * $price) * (100-$discount) / 100;
    $tax_amount = $amount * $tax / 100;
    $amount += $tax_amount;

    $amount = round($amount, 2);
    $tax_amount = round($tax_amount, 2);

    if($product != '' && $quantity != ''){

        $items['product'][$index] = $product;
        $items['group'][$index] = $group;
        $items['quantity'][$index] = $quantity;
        $items['unit'][$index] = $unit;
        $items['price'][$index] = $price;
        $items['discount'][$index] = $discount;
        $items['hsn'][$index] = $hsn;
        $items['tax'][$index] = $tax;
        $items['desc'][$index] = $description;
        $items['long_desc'][$index] = $add_description;
        $items['tax_amount'][$index] = $tax_amount;
        $items['amount'][$index] = $amount;
    }

    $item=json_encode($items);

    $status=0;

    $sql_update = "UPDATE quotation SET `items` = '$item' WHERE `quotation_no` = '$q_id'";
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