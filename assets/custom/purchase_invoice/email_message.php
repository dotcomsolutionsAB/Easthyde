<?php

require_once "../connect.php";

$memberId = $_REQUEST['member_id'];

$result = array("email"=>"", "subject"=>"", "em_message"=>"", "status"=>"400");

$sql = "SELECT * FROM purchase_invoice WHERE pi_no = '$memberId'";
$query = $db->query($sql);
$row = $query->fetch_assoc();

$result['email'] = "";
$result['subject'] = "Purchase Invoice - ".$row['pi_no'];
$result['em_message'] = "Dear Sir/Madam,<br/> Please find the purchase invoice attached to this email.<br/><br/><i>Thanking You,</i></br><b>Ammar Industrial Corporation</b><br/>83/85 NETAJI SUBHASH ROAD, ROOM #A33, GROUND FLOOR<br/>KOLKATA - 700 001, WEST BENGAL, INDIA</br>Ph No. (033) 2231-6239/3316-5010/4065-0181 </br>Website : www.easthyde.com";
$result['status'] = "200";

$db->close();
 
echo json_encode($result);

?>