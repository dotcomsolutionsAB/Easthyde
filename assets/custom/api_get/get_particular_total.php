<?php

require_once "../connect.php";

$memberId = $_REQUEST['member_id'];

$total = 0;

$sql = "SELECT * FROM purchase_invoice WHERE `pi_no` = '$memberId'";
$query = $db->query($sql);
while($result = $query->fetch_assoc())
{
	$total = $result['total'];
}

$sql = "SELECT * FROM payments WHERE `py_no` = '$memberId'";
$query = $db->query($sql);
while($result = $query->fetch_assoc())
{
	$total = $result['amount'];
}


$sql = "SELECT * FROM sales_invoice WHERE `si_no` = '$memberId'";
$query = $db->query($sql);
while($result = $query->fetch_assoc())
{
	$total = $result['total'];
}


$sql = "SELECT * FROM receipts WHERE `r_no` = '$memberId'";
$query = $db->query($sql);
while($result = $query->fetch_assoc())
{
	$total = $result['amount'];
}

$db->close();
 
echo json_encode($total);

?>