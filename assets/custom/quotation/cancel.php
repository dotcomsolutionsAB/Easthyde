<?php 
 
include ("../connect.php");
 
$output = array('success' => false, 'messages' => 'Error while cancelling the information');
 
$id = $_REQUEST['member_id'];
 
$sql = "UPDATE quotation SET `client` = '', `quotation_top`='', `items`='',`tax`='',`total`='',`display_hsn`='1',`addons`='',`terms`='',`notes`='',`status`='9',`display_totals`='1',`cancelled` = '1' WHERE id = '$id'";
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