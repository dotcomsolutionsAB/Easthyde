<?php

require_once "../connect.php";

$memberId = $_REQUEST['member_id'] ?? '';

$sql = "SELECT * FROM purchase_invoice WHERE pi_invoice = '$memberId'";
$query = $db->query($sql);
$result = ($query) ? $query->fetch_assoc() : null;

$db->close();

echo json_encode($result ?? (object)[]);

?>
