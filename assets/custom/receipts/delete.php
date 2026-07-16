<?php 
 
include ("../connect.php");
 
$output = array('success' => false, 'messages' => 'Error while removing the member information');
 
$id = $_REQUEST['member_id'];

$sql_counter = "SELECT * FROM counter WHERE `key` = 'receipt'";
$query_counter = $db->query($sql_counter);
$row_counter = $query_counter -> fetch_assoc();
$row_counter_arr = json_decode($row_counter['value'], true);

$sql_temp = "SELECT * FROM receipts ORDER BY id DESC LIMIT 1";
$query_temp = $db->query($sql_temp);
$row_temp = $query_temp->fetch_assoc();
$r_no = $row_temp['r_no'];
$client = $row_temp['client'];

$sales_invoice = json_decode($row_temp['sales_invoice'], true);
$len = sizeof($sales_invoice['si_no']);

for($j=0;$j<$len;$j++){
    $si_no = $sales_invoice['si_no'][$j];
	$amount = $sales_invoice['amount'][$j];

	if($si_no == 'Opening'){
		$sql_temp = "SELECT * FROM clients WHERE name = '$client'";
		$query_temp = $db->query($sql_temp);
		$row_temp = $query_temp->fetch_assoc();

		$paid = $row_temp['paid'] - $amount;

		$sql_update = "UPDATE clients SET paid ='$paid' WHERE name = '$client'";
		$query_update = $db->query($sql_update);
	}

	$received = 0;

	$sql_temp = "SELECT * FROM receipts WHERE sales_invoice LIKE '%$si_no%' AND status = '1' AND id != '$id'";
	$query_temp = $db->query($sql_temp);
	while($row_temp = $query_temp->fetch_assoc()){

		$si_arr = json_decode($row_temp['sales_invoice'], true);
		$l = sizeof($si_arr['si_no']);
		for($i=0;$i<$l;$i++){
			if($si_arr['si_no'][$i] == $si_no){
				$received += $si_arr['amount'][$i];
			}
		}
	}
	$due = $amount - $received;
	if($received == '0'){
		$sql_update = "UPDATE sales_invoice SET status ='0' WHERE si_no = '$si_no'";
		$query_update = $db->query($sql_update);
	}	
    else
    {
    	if($amount >= $due){
	    	$sql_update = "UPDATE sales_invoice SET status ='1' WHERE si_no = '$si_no'";
			$query_update = $db->query($sql_update);
	    }else{
	    	$sql_update = "UPDATE sales_invoice SET status ='2' WHERE si_no = '$si_no'";
			$query_update = $db->query($sql_update);
	    }
	}


}

if($row_temp['id'] == $id)
{
	$row_counter_arr['number'][0] = $row_counter_arr['number'][0] - 1;
	$counter_array = json_encode($row_counter_arr);
    $sql_counter = "UPDATE counter SET `value` = '$counter_array' WHERE `key` = 'receipt'";
    $query_counter = $db->query($sql_counter);
}
 
$sql = "DELETE FROM receipts WHERE id = '$id'";
$query = $db->query($sql);

if($query === TRUE) {
    $output['success'] = true;
    $output['messages'] = 'Successfully Deleted';
	$output['r_no'] = $r_no;

} else {
    $output['success'] = false;
    $output['messages'] = 'Error while removing the member information';
}
 
echo json_encode($output);
?>