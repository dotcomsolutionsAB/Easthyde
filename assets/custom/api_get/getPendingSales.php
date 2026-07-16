<?php

require_once "../connect.php";
setlocale(LC_MONETARY, 'en_IN');

$memberId = urldecode($_REQUEST['member_id']);
$rc_id = urldecode($_REQUEST['rc_type']);

$response = array("id"=>array(), "si_details_sn"=>array(), "si_details_si"=>array(), "si_details_date"=>array(), "si_details_amount"=>array(), "due"=>array());

$serial_no = 1;

$sql_opening = "SELECT * FROM clients WHERE name = '$memberId'";
$query_opening = $db->query($sql_opening);
$row_opening = $query_opening->fetch_assoc();

if($row_opening['opening_balance'] != ''){

	$received = $row_opening['paid'];

	if($row_opening['opening_balance'] > $received){
		$response['id'][] 					= 'Opening';
		$response['si_details_sn'][] 		= $serial_no;
		$response['si_details_si'][]		= 'Opening';
		$response['si_details_date'][]		= 'N/A';
		$response['si_details_amount'][]	= money_format('%!i', $row_opening['opening_balance'] - $received);
		$response['due'][] 					= money_format('%!i', $row_opening['opening_balance'] - $received);
		$serial_no++;
	}
}

$sql = "SELECT * FROM sales_invoice WHERE client_name = '$memberId' AND `status` != 1  AND cancelled != 1 AND series LIKE '$rc_id' ORDER BY si_date, si_no;";
$query = $db->query($sql);

while($row = $query->fetch_assoc()){

	$amount = 0;
	$sales_invoice = $row['si_no'];
	$sales_date = date('d-m-Y', strtotime($row['si_date']));
	$status = $row['status'];

	$amount = $row['total'];
    
    if($status == '0')
    {
		$response['id'][]					= $row['id'];
		$response['si_details_sn'][] 		= $serial_no;
		$response['si_details_si'][]		= $sales_invoice;
		$response['si_details_date'][]		= $sales_date;
		$response['si_details_amount'][]	= money_format('%!i', $amount);
		$response['due'][] 					= money_format('%!i', $amount);
	}else{
		$received = 0;

		$sql_temp = "SELECT * FROM receipts WHERE sales_invoice LIKE '%$sales_invoice%' AND status = '1' ";
		$query_temp = $db->query($sql_temp);
		while($row_temp = $query_temp->fetch_assoc()){

			$si_arr = json_decode($row_temp['sales_invoice'], true);
			$len = sizeof($si_arr['si_no']);
			for($i=0;$i<$len;$i++){
				if($si_arr['si_no'][$i] == $sales_invoice){
					$received += $si_arr['amount'][$i];
				}
			}
		}

		$response['id'][]					= $row['id'];
		$response['si_details_sn'][] 		= $serial_no;
		$response['si_details_si'][]		= $sales_invoice;
		$response['si_details_date'][]		= $sales_date;
		$response['si_details_amount'][]	= money_format('%!i', $amount - $received);
		$response['due'][] 					= money_format('%!i', $amount - $received);
	}
	$serial_no++;

}

$data = array("result"=>json_encode($response));

$db->close();
 
echo json_encode($data);

?>