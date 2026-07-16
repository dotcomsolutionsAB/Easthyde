<?php

require_once "../connect.php";

$memberId = $_REQUEST['member_id'];

$count = '';
$flag = '';


$sql = "SELECT COUNT(*) as total FROM clients WHERE name = '$memberId'";
$query = $db->query($sql);
$result = $query->fetch_assoc();

$count = $result['total'];
if($count > 0)
{
	$flag = 1;
}

$sql = "SELECT COUNT(*) as total FROM suppliers WHERE name = '$memberId'";
$query = $db->query($sql);
$result = $query->fetch_assoc();

$count = $result['total'];
if($count > 0)
{
	$flag = 0;
}


$db->close();
 
echo json_encode($flag);

?>