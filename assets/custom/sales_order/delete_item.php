<?php
    include ("../connect.php");
    include ("../php_replace_improper.php");

    session_start();

    $validator = array("success"=>true, "messages"=>"There was some error saving the records", 'so'=>'');

    $so_id = $_REQUEST['member_id'] ?? '';
    $index = $_REQUEST['index'] ?? '';

    $new_items=array('product'=>array(),'group'=>array(),'quantity'=>array(),'unit'=>array(),'price'=>array(),'discount'=>array(),'hsn'=>array(),'tax'=>array(),'desc'=>array(),'long_desc'=>array(),'tax_amount'=>array(),'amount'=>array());

    $sql = "SELECT * FROM sales_order WHERE `id` = '$so_id'";
    $query = $db->query($sql);
    $row = ($query && ($tmp = $query->fetch_assoc())) ? $tmp : null;
    if (!$row) {
        $validator['success'] = false;
        $validator['messages'] = "Record not found";
        echo json_encode($validator);
        exit;
    }

    $items = json_decode($row['items'] ?? '', true);
    if (!is_array($items) || !isset($items['product']) || !is_array($items['product'])) {
        $items = ['product'=>[], 'group'=>[], 'quantity'=>[], 'received'=>[], 'unit'=>[], 'price'=>[], 'discount'=>[], 'hsn'=>[], 'tax'=>[], 'desc'=>[], 'long_desc'=>[], 'tax_amount'=>[], 'amount'=>[]];
    }
    foreach (['group','quantity','received','unit','price','discount','hsn','tax','desc','long_desc','tax_amount','amount'] as $key) {
        if (!isset($items[$key]) || !is_array($items[$key])) { $items[$key] = []; }
    }
    $l=sizeof($items['product']);

    for($i=0;$i<$l;$i++){
        if($i != $index){
            $new_items['product'][] = $items['product'][$i];
            $new_items['group'][] = $items['group'][$i];
            $new_items['quantity'][] = $items['quantity'][$i];
            $new_items['received'][] = $items['received'][$i];
            $new_items['unit'][] = $items['unit'][$i];
            $new_items['price'][] = $items['price'][$i];
            $new_items['discount'][] = $items['discount'][$i];
            $new_items['hsn'][] = $items['hsn'][$i];
            $new_items['tax'][] = $items['tax'][$i];
            $new_items['desc'][] = $items['desc'][$i];
            $new_items['long_desc'][] = $items['long_desc'][$i];
            $new_items['tax_amount'][] = $items['tax_amount'][$i];
            $new_items['amount'][] = $items['amount'][$i];
        }
    }
    $item=json_encode($new_items);


    $sql_update = "UPDATE sales_order SET `items` = '$item' WHERE `id` = '$so_id'";
    $query_update = $db->query($sql_update);

    if($query_update === true)
    {
        $validator['success'] = true;
        $validator['messages'] = "Successfully Deleted";
        $validator['so'] = $so;
    }
    else
    {
        $validator['success'] = false;
        $validator['messages'] = "There was some error deleting the records";

    }

    echo json_encode($validator);
?>
