<?php

require_once "../connect.php";
setlocale(LC_MONETARY, 'en_IN');

$memberId = urldecode($_REQUEST['member_id']);

$response = array("id"=>array(), "pi_details_sn"=>array(), "pi_details_pi"=>array(), "pi_details_date"=>array(), "pi_details_amount"=>array(), "due"=>array());

$serial_no = 1;

$sql_opening = "SELECT * FROM suppliers WHERE name = '$memberId'";
$query_opening = $db->query($sql_opening);
$row_opening = $query_opening->fetch_assoc();

$start_year = date('Y', strtotime($start));
$end_year = date('Y', strtotime($end));

$year = $start_year.'-'.substr($end, 2,2);

$new_opening_balance = json_decode($row_fetch['new_opening_balance'],true);
$len = sizeof($new_opening_balance['year']);

for($i=0;$i<$len;$i++)
{
    if($new_opening_balance['year'][$i] == $year)
    {
        $opening = $new_opening_balance['balance'][$i];
    }
}

if($opening != ''){

	$received = $row_opening['paid'];

	if($opening > $received){
		$response['id'][] 					= 'Opening';
		$response['pi_details_sn'][] 		= $serial_no;
		$response['pi_details_pi'][]		= 'Opening';
		$response['pi_details_date'][]		= 'N/A';
		$response['pi_details_amount'][]	= money_format('%!i', $opening - $received);
		$response['due'][] 					= money_format('%!i', $opening - $received);
		$serial_no++;
	}
}

$sql = "SELECT * FROM purchase_invoice WHERE supplier_name = '$memberId' AND status != '1'  ORDER BY pi_date, pi_no";
$query = $db->query($sql);
while($row = $query->fetch_assoc()){

	$amount = 0;
	$purchase_invoice = $row['pi_no'];
	$purchase_date = date('d-m-Y', strtotime($row['pi_date']));
	$status = $row['status'];

	$amount = $row['total'];
    
    if($status == '0')
    {
		$response['id'][]					= $row['id'];
		$response['pi_details_sn'][] 		= $serial_no;
		$response['pi_details_pi'][]		= $purchase_invoice;
		$response['pi_details_date'][]		= $purchase_date;
		$response['pi_details_amount'][]	= money_format('%!i', $amount);
		$response['due'][] 					= money_format('%!i', $amount);
	}else{
		$received = 0;

		$sql_temp = "SELECT * FROM payments WHERE purchase_invoice LIKE '%$purchase_invoice%' AND supplier = '$memberId' AND status = '1' ";
		$query_temp = $db->query($sql_temp);
		while($row_temp = $query_temp->fetch_assoc()){

			$pi_arr = json_decode($row_temp['purchase_invoice'], true);
			$len = sizeof($pi_arr['pi_no']);
			for($i=0;$i<$len;$i++){
				// $received = $pi_arr['pi_no'][$i];
				if($pi_arr['pi_no'][$i] == $purchase_invoice){
					$received += (float)$pi_arr['amount'][$i];
				}
			}
		}

		$response['id'][]					= $row['id'];
		$response['pi_details_sn'][] 		= $serial_no;
		$response['pi_details_pi'][]		= $purchase_invoice;
		$response['pi_details_date'][]		= $purchase_date;
		$response['pi_details_amount'][]	= money_format('%!i', $amount - $received);
		$response['due'][] 					= money_format('%!i', $amount - $received);
	}
	$serial_no++;

}

$data = array("result"=>json_encode($response));

$db->close();
 
echo json_encode($data);

?>