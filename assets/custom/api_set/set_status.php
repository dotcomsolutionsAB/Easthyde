<?php

require_once "../connect.php";

$memberId = $_REQUEST['member_id'];
$status = $_REQUEST['status'];
$script = $_REQUEST['script'];

if($script=="quotation")
{
	$sql = "UPDATE quotation SET `status` = '$status' WHERE `quotation_no`= '$memberId' ";
}
else if($script=="sales_order")
{
	$sql = "UPDATE sales_order SET `status` = '$status' WHERE `so_no`= '$memberId' ";
}
else if($script=="sales_invoice")
{
	$sql = "UPDATE sales_invoice SET `status` = '$status' WHERE `si_no`= '$memberId' ";
}
else if($script=="purchase_invoice")
{
	$sql = "UPDATE purchase_invoice SET `status` = '$status' WHERE `pi_no`= '$memberId' ";
}
else if($script=="purchase_order")
{
	$sql = "UPDATE purchase_order SET `status` = '$status' WHERE `po_no`= '$memberId' ";
}
else if($script=="enquiry")
{
	$sql = "UPDATE enquiry SET `status` = '$status' WHERE `enquiry_no`= '$memberId' ";
}
$query = $db->query($sql);

$db->close();

if($query===true)
    {
        $validator['success'] = true;
        $validator['messages'] = "Status Changed";
    }
else
    {
        $validator['success'] = false;
        $validator['messages'] = "There was some error saving the records";

    }
 
echo json_encode($validator);

?>