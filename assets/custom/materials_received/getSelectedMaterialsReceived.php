<?php

require_once "../connect.php";

$memberId = $_REQUEST['member_id'] ?? '';

$sql = "SELECT * FROM materials_received WHERE id = '$memberId'";
$query = $db->query($sql);
$result = ($query) ? $query->fetch_assoc() : null;

$db->close();

echo json_encode($result ?? (object)[]);

?>