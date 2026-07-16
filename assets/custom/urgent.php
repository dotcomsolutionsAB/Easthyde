<?php
session_start();
require_once "connect.php";
setlocale(LC_MONETARY, 'en_IN');


$sql = "SELECT * FROM `clients`";
$query = $db->query($sql);
while($row = $query->fetch_assoc()){

	$id = $row['id'];

	$opening = json_decode($row['new_opening_balance'], true);

	$new_opening['year'][0] = $opening['year'][0];
	$new_opening['year'][1] = $opening['year'][1];
	$new_opening['year'][2] = $opening['year'][2];
	$new_opening['year'][3] = $opening['year'][3];

	$new_opening['balance'][0] = $opening['balance'][0];
	$new_opening['balance'][1] = $opening['balance'][1];
	$new_opening['balance'][2] = $opening['balance'][2];
	$new_opening['balance'][3] = $opening['balance'][3];

	$new_opening_json = json_encode($new_opening);

	$sql_add = "UPDATE clients SET `new_opening_balance` = '$new_opening_json' WHERE `id` = '$id'";
	// $query_add = $db->query($sql_add);

	echo $sql_add.'<br/>';

}


?>