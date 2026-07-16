<?php
    include ("../connect.php");
    include ("../php_replace_improper.php");

    session_start();

    $validator = array("success"=>true, "messages"=>"There was some error saving the records", 'so'=>'');

    $si_id = $_REQUEST['ai_si_id'];
    $product = replace_improper($_REQUEST['ai_si_product']);
    $description = replace_improper($_REQUEST['ai_si_description']);
    $long_description = $_REQUEST['ai_si_product_add_description'];
    $quantity = replace_improper($_REQUEST['ai_si_quantity']);
    $unit = replace_improper($_REQUEST['ai_si_unit']);
    $price = replace_improper($_REQUEST['ai_si_price']);
    $discount = replace_improper($_REQUEST['ai_si_dsc']);
    $hsn = replace_improper($_REQUEST['ai_si_hsn']);
    $tax = replace_improper($_REQUEST['ai_si_tax']);

    $group=0;

    $s = $long_description;
    $s=str_replace("\"","",$s);
    $s=str_replace("'","",$s);
    $add_description = str_replace(array("\r\n","\r","\n"),'|',trim($s));

    $amount = ($quantity * $price) * (100-$discount) / 100;
    $tax_amount = $amount * $tax / 100;

    $sql = "SELECT * FROM sales_invoice WHERE `id` = '$si_id'";
    $query = $db->query($sql);
    $row = $query->fetch_assoc();

    $si = $row['si_no'];

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

    $sql_update = "UPDATE sales_invoice SET `items` = '$item' WHERE `id` = '$si_id'";
    $query_update = $db->query($sql_update);

    if($query_update === true)
    {
        $validator['success'] = true;
        $validator['messages'] = "Successfully Added";
        $validator['si'] = $si;
    }
    else
    {
        $validator['success'] = false;
        $validator['messages'] = "There was some error saving the records";

    }

    echo json_encode($validator);
?>