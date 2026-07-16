<?php
    include ("../connect.php");
    include ("../php_replace_improper.php");

    session_start();

    $validator = array("success"=>true, "messages"=>"There was some error saving the records");

    $e_id = $_REQUEST['edit_ei_id'];
    $index = $_REQUEST['edit_e_item_id'];
    $product = replace_improper($_REQUEST['edit_e_product_name']);
    $description = replace_improper($_REQUEST['edit_e_product_description']);
    $quantity = replace_improper($_REQUEST['edit_e_qty']);
    $stock = replace_improper($_REQUEST['edit_e_stock']);

    $s = $_REQUEST['edit_e_product_add_description'];
    $s=str_replace("\"","",$s);
    $s=str_replace("'","",$s);
    $add_description = str_replace(array("\r\n","\r","\n"),'|',trim($s));
    
    $sql = "SELECT * FROM enquiry WHERE `enquiry_no` = '$e_id'";
    $query = $db->query($sql);
    $row = $query->fetch_assoc();

    // $po = $row['po_no'];

    $items = json_decode($row['items'], true);

    if($product != '' && $quantity != ''){

        $items['product'][$index] = $product;
        $items['quantity'][$index] = $quantity;
        $items['desc'][$index] = $description;
        $items['long_desc'][$index] = $add_description;
        $items['stock'][$index] = $stock;
    }

    $item=json_encode($items);

    $status=0;

    $sql_update = "UPDATE enquiry SET `items` = '$item' WHERE `enquiry_no` = '$e_id'";
    $query_update = $db->query($sql_update);

    if($query_update === true)
    {
        $validator['success'] = true;
        $validator['messages'] = "Successfully Added";
    }
    else
    {
        $validator['success'] = false;
        $validator['messages'] = "There was some error saving the records";

    }

    echo json_encode($validator);
?>