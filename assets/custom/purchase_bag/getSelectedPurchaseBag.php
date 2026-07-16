<?php

require_once "../connect.php";

$memberId = $_REQUEST['member_id'];
$id='('.$memberId.')';

$response = array("items"=>array(), "quantity"=>array());

$sql_update = "UPDATE purchase_bag SET temp = '1' WHERE id IN $id";
$query_update = $db->query($sql_update);

$sql = "SELECT * FROM purchase_bag WHERE id IN $id ORDER BY `id`,`date`";
$query = $db->query($sql);
while($result = $query->fetch_assoc()){
	$response['items'][] = $result['product_name'];
	$response['quantity'][] = $result['quantity'];
}

$output = array("data"=>json_encode($response));

$db->close();
 
echo json_encode($output);

?>