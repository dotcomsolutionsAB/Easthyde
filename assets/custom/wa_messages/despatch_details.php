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

$output = array("message"=>"", "status"=>"400");

$sql_fetch = "SELECT * FROM sales_invoice WHERE id = '$id'";
$query_fetch = $db->query($sql_fetch);
$row_fetch = $query_fetch->fetch_assoc();

$client = $row_fetch['client_name'];

$sql_temp = "SELECT * FROM clients WHERE name = '$client'";
$query_temp = $db->query($sql_temp);
$row_temp = $query_temp->fetch_assoc();

//Message Creation
$output['message'] = 'Kind Attention: *M/S '.$row_temp['print_name'].'*

Please be informed that your ordered material has been dispatched.
_*Dispatch Details*_ as under:

';

$output['message'] .= '*Invoice No. : '.$row_fetch['si_no'].'*
';
$output['message'] .= '*Dt :* '.date('d-m-Y',strtotime($row_fetch['si_date'])).'
';

$invoice_details = json_decode($row_fetch['invoice_details'], true);

$output['message'] .= '*Transporter :* _'.$invoice_details['despatch_medium'].'_
';
$output['message'] .= '*Document No :* _'.$invoice_details['despatch_doc_no'].'_
';
$temp = '';
if($invoice_details['despatch_date'] !='' && $invoice_details['despatch_date'] != '0000-00-00'){
	$temp = date('d-m-Y',strtotime($invoice_details['despatch_date']));
}
$output['message'] .= '*Date :* _'.$temp.'_
';
$output['message'] .= '*Destination :* _'.$invoice_details['despatch_destination'].'_
';

$output['message'] .= '
*Thanking You*,
*Easthyde*
_Ph_ : *6289778473*
_Email_ : mmleind@gmail.com
_Website_ : www.easthyde.com

Your *feedback* is important to us. Click for *reviews/ratings*:
*https://bit.ly/reviewAIC*';
$output['status'] = "200";

$db->close();
 
echo json_encode($output);

?>