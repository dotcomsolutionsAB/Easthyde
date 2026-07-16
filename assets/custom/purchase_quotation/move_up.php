<?php
    include ("../connect.php");
    include ("../php_replace_improper.php");
    session_start();

    $validator = array("success"=>true, "messages"=>"There was some error saving the records");

    $pi_id = $_REQUEST['member_id'];
    $index = $_REQUEST['index'];
   

    $sql = "SELECT * FROM purchase_invoice WHERE `id` = '$pi_id'";
    $query = $db->query($sql);
    $row = $query->fetch_assoc();

    $old_items = json_decode($row['items'], true);
    $items = json_decode($row['items'], true);
    $prev_index=$index-1;

    $new_items_product= $items['product'][$index];
    $new_items_group= $items['group'][$index];
    $new_items_quantity= $items['quantity'][$index];
    $new_items_unit= $items['unit'][$index];
    $new_items_price= $items['price'][$index];
    $new_items_discount= $items['discount'][$index];
    $new_items_hsn= $items['hsn'][$index];
    $new_items_tax = $items['tax'][$index];
    $new_items_desc= $items['desc'][$index];
    $new_items_long_desc= $items['long_desc'][$index];
    $new_items_tax_amount= $items['tax_amount'][$index];
    $new_items_amount= $items['amount'][$index];

    $items['product'][$index] = $items['product'][$prev_index];
    $items['group'][$index] = $items['group'][$prev_index];
    $items['quantity'][$index] = $items['quantity'][$prev_index];
    $items['unit'][$index] = $items['unit'][$prev_index];
    $items['price'][$index] = $items['price'][$prev_index];
    $items['discount'][$index] = $items['discount'][$prev_index];
    $items['hsn'][$index] = $items['hsn'][$prev_index];
    $items['tax'][$index] = $items['tax'][$prev_index];
    $items['desc'][$index] = $items['desc'][$prev_index];
    $items['long_desc'][$index] = $items['long_desc'][$prev_index];
    $items['tax_amount'][$index] = $items['tax_amount'][$prev_index];
    $items['amount'][$index] = $items['amount'][$prev_index];

    $items['product'][$prev_index] = $new_items_product;
    $items['group'][$prev_index] = $new_items_group;
    $items['quantity'][$prev_index] = $new_items_quantity;
    $items['unit'][$prev_index] = $new_items_unit;
    $items['price'][$prev_index] = $new_items_price;
    $items['discount'][$prev_index] = $new_items_discount;
    $items['hsn'][$prev_index] = $new_items_hsn;
    $items['tax'][$prev_index] = $new_items_tax;
    $items['desc'][$prev_index] = $new_items_desc;
    $items['long_desc'][$prev_index] = $new_items_long_desc;
    $items['tax_amount'][$prev_index] = $new_items_tax_amount;
    $items['amount'][$prev_index] = $new_items_amount;


    $item=json_encode($items);

    $sql_update = "UPDATE purchase_invoice SET `items` = '$item' WHERE `id` = '$pi_id'";
    $query_update = $db->query($sql_update);

    if($query_update === true)
    {
        $validator['success'] = true;
        $validator['messages'] = "Successfully Deleted";
    }
    else
    {
        $validator['success'] = false;
        $validator['messages'] = "There was some error deleting the records";

    }
      echo json_encode($validator);
?>