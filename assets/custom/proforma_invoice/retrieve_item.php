<?php
session_start();
require_once "../connect.php";

$id = $_REQUEST['id'];  

$pagination = $_REQUEST['pagination'];  
$query_array = $_REQUEST['query'];  
$sort_array = $_REQUEST['sort'];  

$sql = "SELECT * FROM proforma WHERE `pr_no` = '$id'";
$query = $db->query($sql);
$row = $query->fetch_assoc();

$item_details = json_decode($row['items'], true);
$l = sizeof($item_details['product']);

$perpage = $pagination['perpage'];
$start = ($pagination['page']-1)*$perpage;
$pages = $l / $perpage;

$output = array('meta'=> array("page"=> $pagination['page'], "pages"=> $pages, "perpage"=> $perpage,"total"=> $l,"sort"=> 'asc', "field"=> 'RecordID'), 'data' => array());
$count=1;

$params = array("so_id"=>$id, "index"=>"");

for($i=0;$i<$l;$i++)
{
    $name=$item_details['product'][$i];
    $qty=$item_details['quantity'][$i];
    $actionBtn = '<a data-toggle="modal" data-target="#kt_modal_add_purchase_bag" class="btn btn-sm btn-clean btn-icon btn-icon-sm" onclick="PurchaseBagLoadSO(\''.$name.'\',\''.$qty.'\')" title="Add to Purchase Bag"><i class="kt-nav__link-icon flaticon2-send-1"  ></i></a>';

    $params['index'] = $i;

    $param = json_encode($params);

    $url = "<a href='?page=product_details&pr=".$item_details['product'][$i]."' target='_blank'>".strtoupper($item_details['product'][$i])."</a>";

    $output['data'][] = array(    
            'RecordID'=>$count++,
            'Product' => $url,
            'Description' => $item_details['desc'][$i],
            'Quantity' => $item_details['quantity'][$i],
            'Received' => $item_details['received'][$i],
            'Price' => $item_details['price'][$i],
            'Discount' => $item_details['discount'][$i],
            'HSN' => $item_details['hsn'][$i],
            'Tax' => $item_details['tax'][$i],
            'Actions' => $actionBtn
    );
}
echo json_encode($output);
?>