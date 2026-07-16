<?php

include ("connect.php");
session_start();

$sql = "SELECT * FROM purchase_invoice WHERE `pi_date` BETWEEN '2021-08-01' AND '2021-08-31'";
$query = $db->query($sql);
while($row = $query->fetch_assoc()){

	$total_amount = 0;
	$total_tax = 0;

	$addons_array = json_decode($row['addons'], true);
	$tax_array = json_decode($row['tax'], true);

	$items = json_decode($row['items'], true);
	$len = sizeof($items['product']);

	for($i=0;$i<$len;$i++){
		$line_total = $items['quantity'][$i]*$items['price'][$i]*(100-$items['discount'][$i])/100;
		$total_amount += round($line_total,2);
		$total_tax += $items['cgst'][$i] + $items['sgst'][$i] + $items['igst'][$i];
	}

	$total_tax += $addons_array['freight']['cgst'] + $addons_array['freight']['sgst'] + $addons_array['freight']['igst'];
	$total_tax += $addons_array['pf']['cgst'] + $addons_array['pf']['sgst'] + $addons_array['pf']['igst'];

	$total_amount += $addons_array['pf']['value'] + $addons_array['freight']['value'] + $total_tax + $addons_array['roundoff'];

	echo $row['pi_no'].'  -  '.$tax_array['cgst'].' | '.$tax_array['sgst'].' | '.$tax_array['igst'].'<br/>';
}

?>