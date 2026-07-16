<?php
session_start();
require_once "connect.php";

$sql_fetch = "SELECT * FROM clients";
$query_fetch = $db->query($sql_fetch);
while($row_fetch = $query_fetch->fetch_assoc())
{
	$id 	= $row_fetch['id'];
	$paid 	= $row_fetch['paid'];


	$paid_new 				= array('2020-21' =>$paid,'2021-22' =>'0');
	$paid_new 				= json_encode($paid_new);

	$sql_add = "UPDATE clients SET `paid_new` = '$paid_new' WHERE `id` = '$id'";
	$query_add = $db->query($sql_add);

}

echo "Completed";

?>