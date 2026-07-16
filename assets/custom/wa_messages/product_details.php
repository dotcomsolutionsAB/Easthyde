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

$sql_fetch = "SELECT * FROM product WHERE id = '$id'";
$query_fetch = $db->query($sql_fetch);
$row_fetch = $query_fetch->fetch_assoc();

//Message Creation
$output['message'] = '*Product Details*
_'.date('d-m-Y',strtotime($date)).'_

';

$output['message'] .= 'Thank you for contacting M. M. Lucky Enterprises.
We have received an enquiry for the following product :

Item : *'.$row_fetch['description'].'*
Part : *'.$row_fetch['name'].'*
HSN : *'.$row_fetch['hsn'].'*
Brand : *'.$row_fetch['group'].'*
Price : *Rs. '.money_format('%!i',$row_fetch['rate']).'*

';

if($row_fetch['url'] != ''){
	$output['message'] .= 'Please find all the technical details at '.$row_fetch['url'].'

';
}

$output['message'] .= 'Thanking You,
*M. M. Lucky Enterprises.*
www.easthyde.com';
$output['status'] = "200";

$db->close();
 
echo json_encode($output);

?>