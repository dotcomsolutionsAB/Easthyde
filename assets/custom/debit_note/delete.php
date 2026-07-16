<?php 
 
include ("../connect.php");
 
$output = array('success' => false, 'messages' => 'Error while removing the member information');
 
$id = $_REQUEST['member_id'];

$sql_counter = "SELECT * FROM counter WHERE `key` = 'debit_note'";
$query_counter = $db->query($sql_counter);
$row_counter = $query_counter -> fetch_assoc();
$row_counter_arr = json_decode($row_counter['value'], true);

$row_counter_arr['number'][0] = $row_counter_arr['number'][0] - 1;
$counter_array = json_encode($row_counter_arr);

$sql_counter = "UPDATE counter SET `value` = '$counter_array' WHERE `key` = 'debit_note'";
$query_counter = $db->query($sql_counter);
 
$sql = "DELETE FROM debit_note WHERE id = '$id'";
$query = $db->query($sql);

if($query === TRUE) {
    $output['success'] = true;
    $output['messages'] = 'Successfully Deleted';

} else {
    $output['success'] = false;
    $output['messages'] = 'Error while removing the member information';
}
 
echo json_encode($output);
?>