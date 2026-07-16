<?php
include ("connect.php");

session_start();

$sql = "SELECT * FROM purchase_invoice WHERE pi_date BETWEEN '2022-07-01' AND '2022-07-31'";
$query = $db->query($sql);
while($row = $query->fetch_assoc()){

	$id = $row['id'];

	$date = $row['pi_date'];
	$pi_no = $row['pi_no'];
	$total = TrimTrailingZeroes(number_format($row['total'],2, '.', ''));
	$addons = json_decode($row['addons'], true);
	$tax = json_decode($row['tax'], true);

	if($tax['cgst'] != '')
		$tax['cgst'] = number_format($tax['cgst'],2, '.', '');
	if($tax['sgst'] != '')
		$tax['sgst'] = number_format($tax['sgst'],2, '.', '');
	if($tax['igst'] != '')
		$tax['igst'] = number_format($tax['igst'],2, '.', '');

	if($addons['roundoff'] != '')
		$addons['roundoff'] = number_format($addons['roundoff'],2, '.', '');

	$addons = json_encode($addons);
	$tax = json_encode($tax);

	$sql_update = "UPDATE purchase_invoice SET `addons` = '$addons', `tax` = '$tax', `total` = '$total' WHERE `id` = '$id'";
	$query_update = $db->query($sql_update);
	echo $sql_update.'<br/><br/>';

	// echo $date.'  -  '.$si_no.'  -  '.$round.'</br></br>';
	// echo $round.' - '.$round_new.'</br>';
	// echo 'Completed';
}

function TrimTrailingZeroes($nbr) {
    return strpos($nbr,'.')!==false ? rtrim(rtrim($nbr,'0'),'.') : $nbr;
}
?>

