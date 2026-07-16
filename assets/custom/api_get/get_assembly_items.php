<?php

session_start();
require_once "../connect.php";

$composite = $_REQUEST['composite'];

$sql = "SELECT * FROM assembly WHERE `composite` = '$composite'";
$query = $db->query($sql);
while($result = $query->fetch_assoc())
{
	$response[] = $result['spares'];
}

$db->close();
 
echo json_encode($response);

?>