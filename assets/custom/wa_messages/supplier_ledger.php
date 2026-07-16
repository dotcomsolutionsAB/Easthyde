<?php
session_start();
require_once "../connect.php";
setlocale(LC_MONETARY, 'en_IN');

$start = $_SESSION['start'];
$end = $_SESSION['end'];
$date = date('Y-m-d',strtotime('today'));

if(strtotime($end) > strtotime($date)){
	$end = $date;
}
$id = $_REQUEST['member_id'];

$output = array("message"=>"", "status"=>"400");

$result=array('particulars'=>array(),'date'=>array(),'voucher'=>array(),'credit'=>array(),'debit'=>array(),'details'=>array());

$sql_fetch = "SELECT * FROM suppliers WHERE id = '$id'";
$query_fetch = $db->query($sql_fetch);
$row_fetch = $query_fetch->fetch_assoc();

$supplier = $row_fetch['name'];
$supplier_print = $row_fetch['print_name'];
$opening = $row_fetch['opening_balance'];
$contacts = json_decode($row_fetch['contacts'], true);
$email=$contacts['email'][0];
$mobile=$contacts['mobile'][0];

$total=0;
$debit=0;
$credit=0;
$d_debit=0;
$c_credit=0;

if($opening != 0){
    $result['particulars'][] = '*Opening Balance*';
    $result['date'][] = $start;
    $result['voucher'][] = '		';
    $result['credit'][] = $opening;
    $result['debit'][] = '';
    $result['details'][] = '';
}

$sql = "SELECT * FROM purchase_invoice WHERE `supplier_name`='$supplier' AND `series` = 'PRIMARY' AND  `pi_date` BETWEEN '$start' AND '$end'  ORDER BY `pi_date` ASC";
$query = $db->query($sql);
while($row = $query->fetch_assoc()){
	$count++;

	$tax_details = json_decode($row['tax'], true);

    $total = $row['total'];
    $tax = $tax_details['cgst'] + $tax_details['sgst'] + $tax_details['igst'];

    $result['particulars'][] = '*Invoice*';
    $result['date'][] = $row['pi_date'];
    $result['voucher'][] = 'Purchase 	';
    $result['credit'][] = $total;
    $result['debit'][] = '';
    $result['details'][] = '_- '.$row['pi_no'].'_';

}

$sql = "SELECT * FROM payments WHERE `supplier`='$supplier' AND `date` BETWEEN '$start' AND '$end' ORDER BY `date` ASC";
$query = $db->query($sql);
while($row = $query->fetch_assoc()){

    $result['particulars'][]    = '*Payment*';
    $result['date'][] 			= $row['date'];
    $result['voucher'][]        = '		';
    $result['credit'][] 		= '';
    $result['debit'][] 			= $row['amount'];
    $result['details'][] 		= '_- '.$row['mode'].' ('.$row['instrument'].')';

}

$len = sizeof($result['date']);

for($m=0;$m<$len-1;$m++){
	for($n=$m+1;$n<$len;$n++){
		if($result['date'][$m] > $result['date'][$n]){
			$temp = $result['date'][$m];
			$result['date'][$m] = $result['date'][$n];
			$result['date'][$n] = $temp;

			$temp = $result['particulars'][$m];
			$result['particulars'][$m] = $result['particulars'][$n];
			$result['particulars'][$n] = $temp;

            $temp = $result['voucher'][$m];
            $result['voucher'][$m] = $result['voucher'][$n];
            $result['voucher'][$n] = $temp;

			$temp = $result['credit'][$m];
			$result['credit'][$m] = $result['credit'][$n];
			$result['credit'][$n] = $temp;

			$temp = $result['debit'][$m];
			$result['debit'][$m] = $result['debit'][$n];
			$result['debit'][$n] = $temp;

			$temp = $result['details'][$m];
			$result['details'][$m] = $result['details'][$n];
			$result['details'][$n] = $temp;
		}
	}
}

//Message Creation
$output['message'] = 'Kind Attention : *M/S '.$supplier_print.'*

Sir / Madam,

Please find the *accounting ledger* as stated under :

*_Period:_  '.date('d-m-Y',strtotime($start)).' - '.date('d-m-Y',strtotime($end)).'*

';

$len = sizeof($result['particulars']);
for($i=0;$i<$len;$i++){
	$count = $i+1;
	$output['message'] .= $count.'. '.$result['particulars'][$i].' '.$result['details'][$i].'
';
	$output['message'] .= '			*Dt: '.date('d-m-Y',strtotime($result['date'][$i])).'*
';

if($result['debit'][$i] != ''){
	$output['message'] .= '			*Rs. '.money_format('%!i', $result['debit'][$i]).'*
';
}
else if($result['credit'][$i] != ''){
	$output['message'] .= '			*Rs. '.money_format('%!i', $result['credit'][$i]).'*
';
}


	$total=$total+$result['credit'][$i]-$result['debit'][$i];
    $debit=$debit+$result['debit'][$i];
    $credit=$credit+$result['credit'][$i];
}

$output['message'] .= '
Total Debit 	: *'.money_format('%!i',$debit).'*
Total Credit 	: *'.money_format('%!i',$credit).'*

Balance Due 	: *'.money_format('%!i',$total).'*

For any query / clarification / discrepancies, please feel free to contact

';

$output['message'] .= 'Thanking You,
*M. M. Lucky Enterprises.*
www.easthyde.com';
$output['status'] = "200";

$db->close();
 
echo json_encode($output);

?>