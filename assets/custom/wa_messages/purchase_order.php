<?php
session_start();
require_once "../connect.php";
setlocale(LC_MONETARY, 'en_IN');

$start = $_SESSION['start'] ?? '';
$end = $_SESSION['end'] ?? '';
$date = date('Y-m-d',strtotime('today'));

if(strtotime($end) > strtotime($date)){
	$end = $date;
}
$id = $_REQUEST['member_id'] ?? '';

$output = array("message"=>"", "status"=>"400","po_no"=>"","mobile"=>"");

$sql_fetch = "SELECT * FROM purchase_order WHERE id = '$id'";
$query_fetch = $db->query($sql_fetch);
$row_fetch = ($query_fetch) ? $query_fetch->fetch_assoc() : null;
if (!$row_fetch) {
	$db->close();
	echo json_encode($output);
	exit;
}

$supplier = $row_fetch['supplier_name'];

$sql_temp = "SELECT * FROM suppliers WHERE name = '$supplier'";
$query_temp = $db->query($sql_temp);
$row_temp = ($query_temp) ? $query_temp->fetch_assoc() : null;
if (!$row_temp) {
	$db->close();
	echo json_encode($output);
	exit;
}

$contact = json_decode($row_temp['contacts'] ?? '', true);

if (!is_array($contact)) {

    $contact = [];

}
$mobile = $contact['mobile'][0];

$output['status'] = "200";
$output['po_no'] = $row_fetch['po_no'];
$output['mobile'] = $mobile;


$db->close();
 
echo json_encode($output);

?>