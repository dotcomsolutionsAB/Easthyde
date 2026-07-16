<?php
    include ("../connect.php");
    include ("../php_replace_improper.php");
    session_start();

	// $log_user       = $_SESSION['username'];
 //    $log_date       = date('Y-m-d', strtotime("today"));

    $validator      = array("success"=>true, "messages"=>"There was some error saving the records");

    $id             = $_REQUEST['assemby_tag_id'];
    $invoice        = $_REQUEST['assemby_invoice'];

    $sql = "UPDATE assembly_operation SET `invoice` = '$invoice' WHERE `id` = '$id' ";
    $query = $db->query($sql);

    if($query===true)
    {
        $validator['success'] = true;
        $validator['messages'] = "Successfully Updated";
    }
    else
    {
        $validator['success'] = false;
        $validator['messages'] = "There was some error saving the records";
    }
    echo json_encode($validator);
?>