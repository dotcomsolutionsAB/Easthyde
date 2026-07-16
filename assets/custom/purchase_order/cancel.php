<?php 
 
include ("../connect.php");
 
$output = array('success' => false, 'messages' => 'Error while cancelling the information');
 
$id = $_REQUEST['member_id'];
 
$sql = "UPDATE purchase_order SET `supplier_name` = '',`top`='',`shipping`='',`items`='',`tax`='',`total`='',`addons`='',`status`='9',`state`='',`cancelled` = '1' WHERE id = '$id'";
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