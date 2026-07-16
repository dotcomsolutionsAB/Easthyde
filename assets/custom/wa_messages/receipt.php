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

$sql_fetch = "SELECT * FROM receipts WHERE id = '$id'";
$query_fetch = $db->query($sql_fetch);
$row_fetch = $query_fetch->fetch_assoc();

$client = $row_fetch['client'];

$sql_temp = "SELECT * FROM clients WHERE name = '$client'";
$query_temp = $db->query($sql_temp);
$row_temp = $query_temp->fetch_assoc();

//Message Creation
$output['message'] = '*Payment Receipt*
_'.date('d-m-Y',strtotime($row_fetch['date'])).'_

Client : *'.$row_temp['print_name'].'*

Total : *Rs. '.money_format('%!i',$row_fetch['amount']).'*

';

if($row_fetch['mode'] == 'CASH'){
	$output['message'] .= 'Mode of Payment : *'.$row_fetch['mode'].'*';
}else{
	$output['message'] .= 'Mode of Payment : *'.$row_fetch['mode'].'*
Bank : *'.$row_fetch['bank_name'].'*
Ref No : *'.$row_fetch['instrument'].'*

';
}

$count = 1;

$sales_invoice = json_decode($row_fetch['sales_invoice'], true);
$len = sizeof($sales_invoice['si_no']);
for($i=0;$i<$len;$i++){

	$output['message'] .= $count++.'   '.$sales_invoice['si_no'][$i].'   *Rs. '.money_format('%!i',$sales_invoice['amount'][$i]).'*
';

}


$output['message'] .= '

Thanking You,
*Easthyde*
_www.easthyde.com_';
$output['status'] = "200";

$db->close();
 
echo json_encode($output);

?>