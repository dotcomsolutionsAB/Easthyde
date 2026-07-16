<?php
    include ("../connect.php");
    include ("../php_replace_improper.php");

    session_start();

    $validator = array("success"=>true, "messages"=>"There was some error saving the records");

    $e_id = $_REQUEST['member_id'] ?? '';
    $index = $_REQUEST['index'] ?? '';

    $new_items=array('product'=>array(),'quantity'=>array(),'stock'=>array(),'desc'=>array(),'long_desc'=>array());

    $sql = "SELECT * FROM enquiry WHERE `id` = '$e_id'";
    $query = $db->query($sql);
    $row = ($query && ($tmp = $query->fetch_assoc())) ? $tmp : null;
    if (!$row) {
        $validator['success'] = false;
        $validator['messages'] = "Record not found";
        echo json_encode($validator);
        exit;
    }

    // $po = $row['q_no'];

    $items = json_decode($row['items'] ?? '', true);
    if (!is_array($items) || !isset($items['product']) || !is_array($items['product'])) {
        $items = ['product'=>[], 'quantity'=>[], 'desc'=>[], 'long_desc'=>[], 'stock'=>[]];
    }
    foreach (['quantity','desc','long_desc','stock'] as $key) {
        if (!isset($items[$key]) || !is_array($items[$key])) { $items[$key] = []; }
    }
    $l=sizeof($items['product']);

    for($i=0;$i<$l;$i++){
        if($i != $index){
            $new_items['product'][] = $items['product'][$i];
            $new_items['quantity'][] = $items['quantity'][$i];
            $new_items['desc'][] = $items['desc'][$i];
            $new_items['long_desc'][] = $items['long_desc'][$i];
            $new_items['stock'][] = $items['stock'][$i];
        }
    }

    $item=json_encode($new_items);

    $sql_update = "UPDATE enquiry SET `items` = '$item' WHERE `id` = '$e_id'";
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
