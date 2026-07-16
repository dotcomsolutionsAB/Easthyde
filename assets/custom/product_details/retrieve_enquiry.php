<?php
session_start();
require_once "../connect.php";

$pagination = $_REQUEST['pagination'];  
$query_array = $_REQUEST['query'];  
$sort_array = $_REQUEST['sort'];  

$dt_start = $_SESSION['start'];
$dt_end = $_SESSION['end'];

$query = $query_array['search_pd_enquiry'];

$pr=$_SESSION['pd_product_name'];

$sql_1 = "SELECT COUNT(*) AS total FROM enquiry WHERE items LIKE '%$pr%' AND (client LIKE '%$query%' OR cl_enquiry_no LIKE '%$query%') AND `enquiry_date` BETWEEN '$dt_start' AND '$dt_end'";
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
$sql = "SELECT * FROM enquiry WHERE items LIKE '%$pr%' AND (client LIKE '%$query%' OR cl_enquiry_no LIKE '%$query%') AND `enquiry_date` BETWEEN '$dt_start' AND '$dt_end' ORDER BY `enquiry_date` DESC LIMIT ".$start.','.$perpage;
$query = $db->query($sql);
while($row = $query->fetch_assoc()){

    $qty = '';
    $rate = '';

    $items = json_decode($row['items'], true);
    $len = sizeof($items['product']);

    for($i=0;$i<$len;$i++){
        if($items['product'][$i] == $pr){
            $qty = $items['quantity'][$i];
            $rate = $items['price'][$i];
        }
    }

    $output['data'][] = array(      
        'SN' => $count++,
        'Client' => $row['client'],
        'EN' => $row['cl_enquiry_no'],
        'E_Date' => date('d-m-Y',strtotime($row['enquiry_date'])),
        'Qty' => $qty
    );
}

echo json_encode($output);

?>