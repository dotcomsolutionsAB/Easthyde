<?php
session_start();
include ("../connect.php");

$pagination = $_REQUEST['pagination'] ?? [];
$query_array = $_REQUEST['query'] ?? [];
$sort_array = $_REQUEST['sort'] ?? [];

$query = $query_array['generalSearch'] ?? '';
$invoice = $query_array['invoice'] ?? '';


$output = array('meta'=> array("page"=> 1, "pages"=> 1, "perpage"=> 1,"total"=> 1,"sort"=> "asc", "field"=> "SN"), 'data' => array());

$count=1;
$sql = "SELECT * FROM assembly_operation WHERE `invoice` = '$invoice' ORDER BY id DESC";
$query = $db->query($sql);
if ($query) {
while($row = $query->fetch_assoc()){

    $items = json_decode($row['items'] ?? '', true);
    $len = (is_array($items) && isset($items['product']) && is_array($items['product'])) ? sizeof($items['product']) : 0;

    $sp = '';
    for($i=0;$i<$len;$i++){
        $sp .= ($items['product'][$i] ?? '').' ('.($items['quantity'][$i] ?? '').'), </br>';
    }

    $sp = rtrim($sp, ', ');

    

	$output['data'][] = array(		
		'SN' => $count++,
        'id' => $row['id'],
        'composite'=>$row['composite'],
        'spares' => $sp,
        'operation'=>$row['operation'],
        'quantity'=>$row['quantity'],
		'log_user' => $row['log_user'],
        'log_date' => $row['log_date'],
	);
}
}

echo json_encode($output);

?>
