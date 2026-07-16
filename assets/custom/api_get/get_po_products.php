<?php
session_start();
require_once "../connect.php";

$id = $_REQUEST['id'];  

$pagination = $_REQUEST['pagination'];  
$query_array = $_REQUEST['query'];  
$sort_array = $_REQUEST['sort'];  

$sql = "SELECT * FROM purchase_order WHERE `po_no` = '$id'";
$query = $db->query($sql);
$row = $query->fetch_assoc();

$item_details = json_decode($row['items'], true);
$l = sizeof($item_details['product']);

$perpage = $pagination['perpage'];
$start = ($pagination['page']-1)*$perpage;
$pages = $l / $perpage;

$output = array('meta'=> array("page"=> $pagination['page'], "pages"=> $pages, "perpage"=> $perpage,"total"=> $l,"sort"=> 'asc', "field"=> 'RecordID'), 'data' => array());
$count=1;

$params = array("po_id"=>$id, "index"=>"");

for($i=0;$i<$l;$i++)
{
    $params['index'] = $i;

    $param = json_encode($params);
    $actionBtn = '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="modal" data-target="#kt_modal_ei_purchase_order" onclick="editItemPurchaseOrder(\''.$row['id'].'\',\''.$i.'\')" title="Edit Purchase Order">
                    <i class="flaticon2-paper"></i>
                </a>
                <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="modal" data-target="#delete_item_purchase_order" title="Delete" onclick="removeItemPurchaseOrder(\''.$row['id'].'\',\''.$i.'\')">
                    <i class="flaticon2-trash"></i>
                </a>';

    $output['data'][] = array(    
            'RecordID'=>$count++,
            'Product' => $item_details['product'][$i],
            'Description' => $item_details['desc'][$i],
            'Quantity' => $item_details['quantity'][$i],
            'Received' => $item_details['received'][$i],
            'Price' => $item_details['price'][$i],
            'Discount' => $item_details['discount'][$i],
            'HSN' => $item_details['hsn'][$i],
            'Tax' => $item_details['tax'][$i],
            'Actions'=> $actionBtn
    );
}
echo json_encode($output);
?>