<?php
session_start();
require_once "../connect.php";

$pagination = $_REQUEST['pagination'];  
$query_array = $_REQUEST['query'];  
$sort_array = $_REQUEST['sort'];  

$dt_start = $_SESSION['start'];
$dt_end = $_SESSION['end'];

$query = $query_array['search_pd_purchase'];

$pr=$_SESSION['pd_product_name'];
$pr_search="\"".$_SESSION['pd_product_name']."\"";

$sql_1 = "SELECT COUNT(*) AS total FROM purchase_invoice WHERE items LIKE '%$pr_search%' AND (supplier_name LIKE '%$query%' OR pi_no LIKE '%$query%') AND `pi_date` BETWEEN '$dt_start' AND '$dt_end'";
$query_1 = $db->query($sql_1);
$row_1 = $query_1->fetch_assoc();

if($pagination['perpage'] != -1)
    $perpage = $pagination['perpage'];
else
    $perpage = $row_1['total'];

$start = ($pagination['page']-1)*$perpage;
$pages = $row_1['total'] / $perpage;

$output = array('meta'=> array("page"=> $pagination['page'], "pages"=> $pages, "perpage"=> $perpage,"total"=> $row_1['total'],"sort"=> 'asc', "field"=> 'SN'), 'data' => array());

$count=1;
$sql = "SELECT * FROM purchase_invoice WHERE items LIKE '%$pr_search%' AND (supplier_name LIKE '%$query%' OR pi_no LIKE '%$query%') AND `pi_date` BETWEEN '$dt_start' AND '$dt_end' ORDER BY `pi_date` DESC LIMIT ".$start.','.$perpage;
$query = $db->query($sql);
while($row = $query->fetch_assoc()){

    $qty = '0';
    $rate = '';

    $items = json_decode($row['items'], true);
    $len = sizeof($items['product']);

    for($i=0;$i<$len;$i++){
        if($items['product'][$i] == $pr){
            $qty += $items['quantity'][$i];
            $rate = $items['price'][$i];
        }
    }

	$output['data'][] = array(		
    	'SN' => $count++,
        'Supplier' => $row['supplier_name'],
        'PI' => "<a href='../assets/custom/purchase_invoice_print.php?id=".$row['id']."&type=print' target='_blank'>".$row['pi_no']."</a>",
        'PI_Date' => date('d-m-Y',strtotime($row['pi_date'])),
        'Qty' => $qty,
        'Rate' => $rate
	);
}

echo json_encode($output);

?>