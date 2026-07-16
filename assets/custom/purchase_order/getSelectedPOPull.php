<?php

require_once "../connect.php";

$memberId = $_REQUEST['member_id'];
$len = sizeof($memberId);
$final_items = array('product'=>array(), 'quantity'=>array(), 'received'=>array(), 'unit'=>array(),'price'=>array(),'discount'=>array(),'hsn'=>array(),'desc'=>array(),'tax'=>array(),'long_desc'=>array());
$final_addons = array('freight'=>'', 'pf'=>'','discount'=>'');

for($i=0;$i<$len;$i++)
{
	$member = $memberId[$i];
	$sql = "SELECT * FROM purchase_order WHERE po_no = '$member'";
	$query = $db->query($sql);
	$result = $query->fetch_assoc();

	$items = json_decode($result['items'], true);
	$item_len = sizeof($items['product']);

	for($j=0;$j<$item_len;$j++){
		$final_items['product'][] = $items['product'][$j];
		$final_items['quantity'][] = $items['quantity'][$j];
		$final_items['received'][] = $items['received'][$j];
		$final_items['unit'][] = $items['unit'][$j];
		$final_items['price'][] = $items['price'][$j];
		$final_items['discount'][] = $items['discount'][$j];
		$final_items['hsn'][] = $items['hsn'][$j];
		$final_items['desc'][] = $items['desc'][$j];
		$final_items['tax'][] = $items['tax'][$j];
		$final_items['long_desc'][] = $items['long_desc'][$j];
	}

	$addons = json_decode($result['addons'], true);

	$final_addons['freight'] += $addons['freight']['value'];
	$final_addons['pf'] += $addons['pf']['value'];
	$final_addons['discount'] += $addons['discount'];
}

$data = array('items'=>json_encode($final_items), 'addons'=>json_encode($final_addons));

$db->close();
 
echo json_encode($data);

?>