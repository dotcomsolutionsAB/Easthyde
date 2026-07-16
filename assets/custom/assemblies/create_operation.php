<?php
    include ("../connect.php");
    include ("../php_replace_improper.php");
    session_start();
	$log_user       = $_SESSION['username'] ?? '';
    $assembly_date_raw = $_REQUEST['assembly_date'] ?? '';
    $log_date       = ($assembly_date_raw !== '') ? date('Y-m-d', strtotime((string)$assembly_date_raw)) : '';
    $validator      = array("success"=>true, "messages"=>"There was some error saving the records");
    $edit_as_id     = $_REQUEST['edit_as_id'] ?? '';

	$composite      = $_REQUEST['composite_product'] ?? '';
    $quantity       = $_REQUEST['composite_qty'] ?? '';
    $operation      = $_REQUEST['as_type'] ?? '';

	$array      = $_REQUEST['assembly'] ?? [];
    $l          = sizeof($array);

    $items = array('product'=>array(),'quantity'=>array(),'place'=>array());

    for($i=0;$i<$l;$i++){
        if($array[$i]['as_product_name'] != '' && $array[$i]['as_qty'] != ''){

            $items['product'][]     = $array[$i]['as_product_name'];
            $items['quantity'][]    = $array[$i]['as_qty'];
            $items['place'][]       = $array[$i]['as_place'];

        }
    }
    $items       = json_encode($items);

    $sql = "INSERT INTO assembly_operation (`composite`,`items`,`operation`,`quantity`,`log_user`,`log_date`) VALUES ('$composite','$items','$operation', '$quantity','$log_user','$log_date')";
        $query = $db->query($sql);

    if($query===true)
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
