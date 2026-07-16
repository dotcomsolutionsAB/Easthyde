<?php

require_once "../connect.php";

$name = $_REQUEST['member_id'];

$sql = "SELECT * FROM product WHERE name = '$name'";
$query = $db->query($sql);
$result = $query->fetch_assoc();

$db->close();
 
echo json_encode($result);

?>