<?php 
 
include ("../connect.php");
 
$output = array('success' => false, 'messages' => 'Error while cancelling the information');
 
$id = $_REQUEST['member_id'];
 
$sql = "UPDATE sales_invoice SET `so_no`='', `q_no`='',`shipping`='',`state`='',`invoice_details`='',`items`='',`tax`='',`hsn_table`='1',`addons`='',`status`='9', `cancelled` = '1' WHERE id = '$id'";
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