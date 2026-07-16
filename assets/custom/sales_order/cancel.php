<?php 
 
include ("../connect.php");
 
$output = array('success' => false, 'messages' => 'Error while cancelling the information');
 
$id = $_REQUEST['member_id'];
 
$sql = "UPDATE sales_order SET `client_name` = '',`q_no`='',`client_so_no`='',`items`='',`tax`='',`total`='',`addons`='',`status`='9',`collected`='0',`cancelled` = '1' WHERE id = '$id'";
$query = $db->query($sql);

if($query === TRUE) {
    $output['success'] = true;
    $output['messages'] = 'Successfully Cancelled';
} else {
    $output['success'] = false;
    $output['messages'] = 'Error while cancelling the information';
}
 
echo json_encode($output);
?>