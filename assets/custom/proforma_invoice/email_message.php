<?php

require_once "../connect.php";

$memberId = $_REQUEST['member_id'];

$result = array("email"=>"", "subject"=>"", "em_message"=>"", "status"=>"400");

$sql = "SELECT * FROM proforma WHERE id = '$memberId'";
$query = $db->query($sql);
$row = $query->fetch_assoc();

$result['email'] = "";
$result['subject'] = "Proforma Invoice - ".$row['pr_no'];
$result['em_message'] = "Dear Sir/Madam,<br/> Please find the proforma invoice attached to this email.<br/><br/><i>Thanking You,</i><br/><strong>Ammar Industrial Corporation</strong><br/>83/85 NETAJI SUBHASH ROAD,<br/>ROOM #A33, GROUND FLOOR<br/>KOLKATA - 700 001, WEST BENGAL, INDIA<br/>Ph No. (033) 2231-6239/7134-2823/4602-7368<br/>Website : www.easthyde.com";
$result['status'] = "200";

$db->close();
 
echo json_encode($result);

?>