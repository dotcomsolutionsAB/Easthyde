<?php
    include ("../connect.php");
    include ("../php_replace_improper.php");

    session_start();

    $validator = array("success"=>true, "messages"=>"There was some error saving the records");

    $e_id = $_REQUEST['ai_e_id'] ?? '';
    $product = replace_improper($_REQUEST['ai_e_product'] ?? '');
    $description = replace_improper(trim((string)($_REQUEST['ai_e_description'] ?? '')));
    $quantity = replace_improper($_REQUEST['ai_e_quantity'] ?? '');
    $long_description = replace_improper(trim((string)($_REQUEST['ai_e_add_description'] ?? '')));
    $stock = replace_improper($_REQUEST['ai_e_stock'] ?? '');


    $sql = "SELECT * FROM enquiry WHERE `id` = '$e_id'";
    $query = $db->query($sql);
    $row = ($query && ($tmp = $query->fetch_assoc())) ? $tmp : null;
    if (!$row) {
        $validator['success'] = false;
        $validator['messages'] = "Record not found";
        echo json_encode($validator);
        exit;
    }

    // $po = $row['po_invoice'];

    $items = json_decode($row['items'] ?? '', true);
    if (!is_array($items)) { $items = ['product'=>[], 'quantity'=>[], 'desc'=>[], 'long_desc'=>[], 'stock'=>[]]; }

    if($product != '' && $quantity != ''){
        $items['product'][] = $product;
        $items['quantity'][] = $quantity;
        $items['desc'][] = $description;
        $items['long_desc'][] = $long_description;
        $items['stock'][] = $stock;
    }

    $item=json_encode($items);

    $status=0;

    $sql_update = "UPDATE enquiry SET `items` = '$item' WHERE `id` = '$e_id'";
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
