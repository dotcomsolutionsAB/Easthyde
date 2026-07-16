<?php
session_start();
require_once "../connect.php";

$id = $_REQUEST['id'];  

$pagination = $_REQUEST['pagination'];  
$query_array = $_REQUEST['query'];  
$sort_array = $_REQUEST['sort'];  

$sql = "SELECT * FROM materials_received WHERE `id` = '$id'";
$query = $db->query($sql);
$row = $query->fetch_assoc();

$item_details = json_decode($row['items'], true);
$l = sizeof($item_details['product']);

$perpage = $pagination['perpage'];
$start = ($pagination['page']-1)*$perpage;
$pages = $l / $perpage;

$output = array('meta'=> array("page"=> $pagination['page'], "pages"=> $pages, "perpage"=> $perpage,"total"=> $l,"sort"=> 'asc', "field"=> 'RecordID'), 'data' => array());
$count=1;

for($i=0;$i<$l;$i++)
{

    $url = "<a href='?page=product_details&pr=".$item_details['product'][$i]."' target='_blank'>".strtoupper($item_details['product'][$i])."</a>";

    $output['data'][] = array(    
            'RecordID'=>$count++,
            'Product' => $url,
            'Description' => $item_details['desc'][$i],
            'Quantity' => $item_details['quantity'][$i],
            'Unit'=>$item_details['unit'][$i],
            'Rate'=> isset($item_details['rate'][$i]) ? $item_details['rate'][$i] : ''
    );
}
echo json_encode($output);
?>