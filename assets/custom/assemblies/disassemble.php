<?php
    include ("../connect.php");
    include ("../php_replace_improper.php");
    session_start();
	$log_user = $_SESSION['username'];
    $log_date = date('Y-m-d', strtotime("today"));
    $validator = array("success"=>true, "messages"=>"There was some error saving the records");
	$composite= $_REQUEST['composite_disassemble'];
	$quantity = $_REQUEST['disassemble_qty'];
	$operation = 'Disassembled';

    $sql_fetch = "SELECT * FROM assembly WHERE composite = '$composite'";
    $query_fetch = $db->query($sql_fetch);
    $row_fetch = $query_fetch->fetch_assoc();

    $items = $row_fetch['spares'];

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