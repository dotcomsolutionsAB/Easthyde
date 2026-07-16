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

$output = array("message"=>"", "status"=>"400","pi_no"=>"");

$sql_fetch = "SELECT * FROM purchase_invoice WHERE id = '$id'";
$query_fetch = $db->query($sql_fetch);
$row_fetch = $query_fetch->fetch_assoc();

$output['status'] = "200";
$output['pi_no'] = $row_fetch['pi_no'];

$db->close();
 
echo json_encode($output);

?>