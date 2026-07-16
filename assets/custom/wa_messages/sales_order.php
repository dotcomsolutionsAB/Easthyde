<?php
session_start();
require_once "../connect.php";
setlocale(LC_MONETARY, 'en_IN');

$start = $_SESSION['start'];
$end = $_SESSION['end'];
$date = date('Y-m-d',strtotime('today'));

if(strtotime($end) > strtotime($date)){
	$end = $date;
}
$id = $_REQUEST['member_id'];

$output = array("message"=>"", "status"=>"400","so_no"=>"","mobile"=>"");

$sql_fetch = "SELECT * FROM sales_order WHERE id = '$id'";
$query_fetch = $db->query($sql_fetch);
$row_fetch = $query_fetch->fetch_assoc();

$client = $row_fetch['client_name'];

$sql_temp = "SELECT * FROM clients WHERE name = '$client'";
$query_temp = $db->query($sql_temp);
$row_temp = $query_temp->fetch_assoc();

$contact = json_decode($row_temp['contacts'], true);
$mobile = $contact['mobile'][0];

$output['status'] = "200";
$output['so_no'] = $row_fetch['so_no'];
$output['mobile'] = $mobile;


$db->close();
 
echo json_encode($output);

?>