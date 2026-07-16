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

$output = array("message"=>"", "status"=>"400");

$sql_fetch = "SELECT * FROM payments WHERE id = '$id'";
$query_fetch = $db->query($sql_fetch);
$row_fetch = ($query_fetch) ? $query_fetch->fetch_assoc() : null;
if (!$row_fetch) {
	$db->close();
	echo json_encode($output);
	exit;
}

$supplier = $row_fetch['supplier'];

$purchase_invoice = json_decode($row_fetch['purchase_invoice'] ?? '', true);

if (!is_array($purchase_invoice)) {

    $purchase_invoice = [];

}
$len = is_array($purchase_invoice['pi_no']) ? sizeof($purchase_invoice['pi_no']) : 0;

$sql_temp = "SELECT * FROM suppliers WHERE name = '$supplier'";
$query_temp = $db->query($sql_temp);
$row_temp = ($query_temp) ? $query_temp->fetch_assoc() : null;
if (!$row_temp) {
	$db->close();
	echo json_encode($output);
	exit;
}

//Message Creation
$output['message'] = '*Kind Attention : M/s '.$row_temp['print_name'].'*

This is to inform you that we have made payment against your invoice, details as under:

*Payment* _Dt :'.date('d-m-Y',strtotime($row_fetch['date'])).'_

';

$inv_len = is_array($purchase_invoice['pi_no']) ? sizeof($purchase_invoice['pi_no']) : 0;

for($i=0;$i<$inv_len;$i++){
	$inv = $purchase_invoice['pi_no'][$i];

	$sql_pi = "SELECT * FROM purchase_invoice WHERE pi_no = '$inv'";
	$query_pi = $db->query($sql_pi);
	$row_pi = ($query_pi) ? $query_pi->fetch_assoc() : null;
	if($i == 0)
		$output['message'] .= 'Invoice No. : *'.$purchase_invoice['pi_no'][$i].'* _Dt: '.date('d-m-Y', strtotime($row_pi['pi_date'])).'_
';
	else
		$output['message'] .= '            : *'.$purchase_invoice['pi_no'][$i].'* _Dt: '.date('d-m-Y', strtotime($row_pi['pi_date'])).'_ 
';
}


if($row_fetch['mode'] == 'CASH'){
	$output['message'] .= '
Mode of Payment : *'.$row_fetch['mode'].'*';
}else{
	$output['message'] .= '
Mode of Payment : *'.$row_fetch['mode'].'*
To your Bank A/c : *'.$row_fetch['bank_name'].'*
Ref No : *'.$row_fetch['instrument'].'*

Amount : *Rs. '.number_format((float)($row_fetch['amount']), 2).'*
';
}

$output['message'] .= '
*Thanks*,
*Easthyde*
_Ph_ : *6289778473*
_Email_ : mmleind@gmail.com
_Website_ : www.easthyde.com';
$output['status'] = "200";

$db->close();
 
echo json_encode($output);

?>