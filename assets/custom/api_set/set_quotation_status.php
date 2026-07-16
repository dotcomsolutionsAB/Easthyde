<?php

require_once "../connect.php";

$memberId = $_REQUEST['member_id'];
$status = $_REQUEST['status'];
$script = $_REQUEST['script'];

if($script=='quotation')
{
	$sql = "UPDATE quotation SET `status` = '$status' WHERE `quotation_no`= '$memberId' ";
}
$query = $db->query($sql);

$db->close();

if($query===true)
    {
        $validator['success'] = true;
        $validator['messages'] = "Switched to completed";
    }
else
    {
        $validator['success'] = false;
        $validator['messages'] = "There was some error saving the records";

    }
 
echo json_encode($validator);

?>