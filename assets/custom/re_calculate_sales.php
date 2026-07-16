<?php
include ("connect.php");

session_start();

$si_no = $_REQUEST['id'];

$sql = "SELECT * FROM sales_invoice WHERE si_no='$si_no'";
$query = $db->query($sql);
while($row = $query->fetch_assoc()){

	$client = $row['client_name'];

	$sql_temp = "SELECT * FROM clients WHERE name = '$client'";
	$query_temp = $db->query($sql_temp);
	$row_temp = $query_temp->fetch_assoc();

	$address = json_decode($row_temp['address'], true);

	$id = $row['id'];
	$state = $row_temp["state"];

	$items = json_decode($row['items'], true);
	$len = sizeof($items['product']);

	$addons = json_decode($row['addons'], true);


	$new_items = array();
	$new_addons = array();
	$new_tax = array();

	$tot_cgst = 0;
	$tot_sgst = 0;
	$tot_igst = 0;
	$tot_amount = 0;

	for($i=0;$i<$len;$i++){
		$new_items['product'][$i] 	= $items['product'][$i];
		$new_items['desc'][$i] 		= $items['desc'][$i];
		$new_items['long_desc'][$i] 	= $items['long_desc'][$i];
		$new_items['group'][$i] 		= $items['group'][$i];
		$new_items['quantity'][$i] 	= $items['quantity'][$i];
		$new_items['unit'][$i] 		= $items['unit'][$i];
		$new_items['price'][$i] 		= $items['price'][$i];
		$new_items['discount'][$i] 	= $items['discount'][$i];
		$new_items['hsn'][$i] 		= $items['hsn'][$i];
		$new_items['tax'][$i] 		= $items['tax'][$i];

		$discount = $items['price'][$i] * $items['discount'][$i] / 100;
		$amount = $items['quantity'][$i] * ($items['price'][$i] - $discount);
		$tot_amount += $amount;

		if($state == "WEST BENGAL"){
			$rate = (int)$items['tax'][$i];
			if($rate == '5' || $rate == '12' || $rate == '18' || $rate == '28'){
				$rate = $rate / 2;
				
				$tax_amount = $amount * $rate / 100;
				$tax_amount = round($tax_amount,2);
			}
			else{
				$rate = 0;
				$tax_amount = 0;
			}
			
			$new_items['cgst'][$i]		= $tax_amount;
			$new_items['sgst'][$i] 		= $tax_amount;

			$tot_cgst += $tax_amount;
			$tot_sgst += $tax_amount;

			//Addons
			$new_addons['freight'] = array();
			$new_addons['pf'] = array();
			$new_addons['discount'] = $addons['discount'];

			$new_addons['freight']['value'] = $addons['freight']['value'];

			if($addons['freight']['value'] != '0' && $addons['freight']['value'] != '0.00' && $addons['freight']['value'] != '')
				$tax_value = $addons['freight']['value'] * 9 / 100;
			else
				$tax_value = 0;
			$new_addons['freight']['cgst'] = round($tax_value,2);
			$new_addons['freight']['sgst'] = round($tax_value,2);

			$new_addons['pf']['value'] = $addons['pf']['value'];

			if($addons['pf']['value'] != '0' && $addons['pf']['value'] != '0.00' && $addons['pf']['value'] != '')
				$tax_value = $addons['pf']['value'] * 9 / 100;
			else
				$tax_value = 0;
			$new_addons['pf']['cgst'] = round($tax_value,2);
			$new_addons['pf']['sgst'] = round($tax_value,2);

		}
		else{
			$rate = (int)$items['tax'][$i];
			if($rate == '5' || $rate == '12' || $rate == '18' || $rate == '28'){
				$rate = $items['tax'][$i];
				$tax_amount = $amount * $rate / 100;
				$tax_amount = round($tax_amount,2);
			}
			else{
				$rate = 0;
				$tax_amount = 0;
			}
			
			$new_items['igst'][$i] 		= $tax_amount;
			$tot_igst += $tax_amount;

			//Addons
			$new_addons['freight'] = array();
			$new_addons['pf'] = array();
			$new_addons['discount'] = $addons['discount'];

			$new_addons['freight']['value'] = $addons['freight']['value'];

			if($addons['freight']['value'] != '0' && $addons['freight']['value'] != '0.00' && $addons['freight']['value'] != '')
				$tax_value = $addons['freight']['value'] * 18 / 100;
			else
				$tax_value = 0;
			$new_addons['freight']['igst'] = round($tax_value,2);

			$new_addons['pf']['value'] = $addons['pf']['value'];
			if($addons['pf']['value'] != '0' && $addons['pf']['value'] != '0.00' && $addons['pf']['value'] != '')
				$tax_value = $addons['pf']['value'] * 18 / 100;
			else
				$tax_value = 0;
			$new_addons['pf']['igst'] = round($tax_value,2);
		}

	}

	$new_tax['cgst'] 	= round($tot_cgst,2);
	$new_tax['sgst'] 	= round($tot_sgst,2);
	$new_tax['igst'] 	= round($tot_igst,2);

	$tot_amount 		+= round($tot_cgst,2) + round($tot_sgst,2) + round($tot_igst,2);
	$tot_amount 		+= round($new_addons['freight']['value'],2) + round($new_addons['freight']['sgst'],2) + round($new_addons['freight']['cgst'],2) + round($new_addons['freight']['igst'],2);
	$tot_amount 		+= round($new_addons['pf']['value'],2) + round($new_addons['pf']['sgst'],2) + round($new_addons['pf']['cgst'],2) + round($new_addons['pf']['igst'],2);
	$tot_amount 		-= round($new_addons['discount'],2);
	



	$tot_amount 		= round($tot_amount,2);

	$decimal = floor($tot_amount);
    $fraction = $tot_amount - $decimal;

    if ($fraction >= 0.5) {
        $add_fraction = 1 - $fraction;
        $tot_amount += $add_fraction;
    } else {
        $add_fraction = -1 * $fraction;
        $tot_amount += $add_fraction;
    }

    $new_addons['roundoff'] = round($add_fraction,2);


	$items_json = json_encode($new_items);
	$addons_json = json_encode($new_addons);
	$tax_json 	= json_encode($new_tax);
	// echo $state;

	$sql_add = "UPDATE sales_invoice SET `items` = '$items_json',`tax` = '$tax_json',`total` = '$tot_amount',`addons` = '$addons_json' WHERE `id` = '$id'";
	$query_add = $db->query($sql_add);
	// echo $sql_add.'<br/>';
}
echo "Completed";

?>