<?php
    include ("../connect.php");
    include ("../php_replace_improper.php");
    session_start();
	$log_user       = $_SESSION['username'] ?? '';
    $assembly_date_raw = $_REQUEST['assembly_date'] ?? '';
    $log_date       = ($assembly_date_raw !== '') ? date('Y-m-d', strtotime((string)$assembly_date_raw)) : '';
    $validator      = array("success"=>false, "messages"=>"There was some error saving the records");
    $edit_as_id     = $_REQUEST['edit_as_id'] ?? '';

	$composite      = replace_improper($_REQUEST['composite_product'] ?? '');
    $quantity       = replace_improper($_REQUEST['composite_qty'] ?? '');
    $operation      = replace_improper($_REQUEST['as_type'] ?? '');

	$array      = $_REQUEST['assembly'] ?? [];
    if (!is_array($array)) { $array = []; }
    $l          = sizeof($array);

    $items = array('product'=>array(),'quantity'=>array(),'place'=>array());

    for($i=0;$i<$l;$i++){
        $row = is_array($array[$i] ?? null) ? $array[$i] : [];
        if(($row['as_product_name'] ?? '') != '' && ($row['as_qty'] ?? '') != ''){

            $items['product'][]     = replace_improper($row['as_product_name'] ?? '');
            $items['quantity'][]    = replace_improper($row['as_qty'] ?? '');
            $items['place'][]       = replace_improper($row['as_place'] ?? '');

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
