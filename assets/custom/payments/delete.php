<?php 
 
include ("../connect.php");
 
$output = array('success' => false, 'messages' => 'Error while removing the member information');
 
$id = $_REQUEST['member_id'];

$sql_counter = "SELECT * FROM counter WHERE `key` = 'payment'";
$query_counter = $db->query($sql_counter);
$row_counter = $query_counter -> fetch_assoc();
$row_counter_arr = json_decode($row_counter['value'], true);

$sql_temp = "SELECT * FROM payments ORDER BY id DESC LIMIT 1";
$query_temp = $db->query($sql_temp);
$row_temp = $query_temp->fetch_assoc();
$py_no = $row_temp['py_no'];

$purchase_invoice = json_decode($row_temp['purchase_invoice'], true);
$len = sizeof($purchase_invoice['pi_no']);

for($j=0;$j<$len;$j++){
    $pi_no = $purchase_invoice['pi_no'][$j];
	$amount = $purchase_invoice['amount'][$j];

	$received = 0;

	$sql_temp = "SELECT * FROM payments WHERE purchase_invoice LIKE '%$pi_no%' AND status = '1' AND id != '$id'";
	$query_temp = $db->query($sql_temp);
	while($row_temp = $query_temp->fetch_assoc()){

		$pi_arr = json_decode($row_temp['purchase_invoice'], true);
		$len = sizeof($pi_arr['pi_no']);
		for($i=0;$i<$l;$i++){
			if($pi_arr['pi_no'][$i] == $pi_no){
				$received += $pi_arr['amount'][$i];
			}
		}
	}
	$due = $amount - $received;
	if($received == '0'){
		$sql_update = "UPDATE purchase_invoice SET status ='0' WHERE pi_no = '$pi_no'";
		$query_update = $db->query($sql_update);
	}	
    else
    {
    	if($amount >= $due){
	    	$sql_update = "UPDATE purchase_invoice SET status ='1' WHERE pi_no = '$pi_no'";
			$query_update = $db->query($sql_update);
	    }else{
	    	$sql_update = "UPDATE purchase_invoice SET status ='2' WHERE pi_no = '$pi_no'";
			$query_update = $db->query($sql_update);
	    }
	}


}

if($row_temp['id'] == $id)
{
	$row_counter_arr['number'][0] = $row_counter_arr['number'][0] - 1;
	$counter_array = json_encode($row_counter_arr);
    $sql_counter = "UPDATE counter SET `value` = '$counter_array' WHERE `key` = 'payment'";
    $query_counter = $db->query($sql_counter);
}
 
$sql = "DELETE FROM payments WHERE id = '$id'";
$query = $db->query($sql);

if($query === TRUE) {
    $output['success'] = true;
    $output['messages'] = 'Successfully Deleted';
    $output['py_no'] = $py_no;
} else {
    $output['success'] = false;
    $output['messages'] = 'Error while removing the member information';
}
 
echo json_encode($output);
?>