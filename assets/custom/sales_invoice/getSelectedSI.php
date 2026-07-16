<?php

require_once "../connect.php";

$memberId = $_REQUEST['member_id'];

$sql = "SELECT * FROM sales_invoice WHERE id = '$memberId'";
$query = $db->query($sql);
$result = $query->fetch_assoc();

$db->close();
 
echo json_encode($result);

?>