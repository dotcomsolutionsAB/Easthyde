<?php 
 
include ("../connect.php");
include ("ensure_table.php");
ensure_debit_note_table($db);
 
$output = array('success' => false, 'messages' => 'Error while removing the member information');
 
$id = (string)($_REQUEST['member_id'] ?? '');
$safeId = $db->real_escape_string($id);

$latest_id = '';
$latest_q = $db->query("SELECT id FROM debit_note ORDER BY id DESC LIMIT 1");
if ($latest_q && ($latest_row = $latest_q->fetch_assoc())) {
    $latest_id = (string)($latest_row['id'] ?? '');
}

$sql_counter = "SELECT * FROM counter WHERE `key` = 'debit_note'";
$query_counter = $db->query($sql_counter);
if ($latest_id !== '' && $latest_id === $id && $query_counter && $query_counter->num_rows > 0) {
    $row_counter = $query_counter->fetch_assoc();
    $row_counter_arr = json_decode($row_counter['value'] ?? '', true);
    if (is_array($row_counter_arr) && isset($row_counter_arr['number'][0])) {
        $row_counter_arr['number'][0] = max(0, (int)$row_counter_arr['number'][0] - 1);
        $counter_array = json_encode($row_counter_arr);
        $safeCounter = $db->real_escape_string($counter_array);

        $sql_counter = "UPDATE counter SET `value` = '$safeCounter' WHERE `key` = 'debit_note'";
        $query_counter = $db->query($sql_counter);
    }
}
 
$sql = "DELETE FROM debit_note WHERE id = '$safeId'";
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
