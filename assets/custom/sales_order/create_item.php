<?php
    include ("../connect.php");
    include ("../php_replace_improper.php");

    session_start();

    $validator = array("success"=>true, "messages"=>"There was some error saving the records", 'so'=>'');

    $so_id = $_REQUEST['ai_so_id'];
    $product = replace_improper($_REQUEST['ai_so_product']);
    $description = replace_improper($_REQUEST['ai_so_description']);
    $long_description = $_REQUEST['ai_so_product_add_description'];
    $quantity = replace_improper($_REQUEST['ai_so_quantity']);
    $unit = replace_improper($_REQUEST['ai_so_unit']);
    $price = replace_improper($_REQUEST['ai_so_price']);
    $discount = replace_improper($_REQUEST['ai_so_dsc']);
    $hsn = replace_improper($_REQUEST['ai_so_hsn']);
    $tax = replace_improper($_REQUEST['ai_so_tax']);

    $group=0;

    $s = $long_description;
    $s=str_replace("\"","",$s);
    $s=str_replace("'","",$s);
    $add_description = str_replace(array("\r\n","\r","\n"),'|',trim($s));

    $amount = ($quantity * $price) * (100-$discount) / 100;
    $tax_amount = $amount * $tax / 100;

    $sql = "SELECT * FROM sales_order WHERE `so_no` = '$so_id'";
    $query = $db->query($sql);
    $row = $query->fetch_assoc();

    $so = $row['so_no'];

    $items = json_decode($row['items'], true);

    if($product != '' && $quantity != ''){
        $sql_temp = "SELECT * FROM product WHERE name = '$product'";
        $query_temp = $db->query($sql_temp);
        $row_temp = $query_temp->fetch_assoc();

        $group = $row_temp['default_make'];

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

    $sql_update = "UPDATE sales_order SET `items` = '$item' WHERE `so_no` = '$so_id'";
    $query_update = $db->query($sql_update);

    if($query_update === true)
    {
        $validator['success'] = true;
        $validator['messages'] = "Successfully Added";
        $validator['so'] = $so;
    }
    else
    {
        $validator['success'] = false;
        $validator['messages'] = "There was some error saving the records";

    }

    echo json_encode($validator);
?>