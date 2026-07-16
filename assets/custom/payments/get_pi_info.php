<?php

require_once "../connect.php";

$memberId = $_REQUEST['member_id'];

$sql = "SELECT * FROM purchase_invoice WHERE pi_invoice = '$memberId'";
$query = $db->query($sql);
$result = $query->fetch_assoc();

$db->close();
 
echo json_encode($result);

?>