<?php
session_start();
require_once "../connect.php";

$pagination = $_REQUEST['pagination'] ?? [];
$query_array = $_REQUEST['query'] ?? [];
$sort_array = $_REQUEST['sort'] ?? [];

$query = $query_array['generalSearch'] ?? '';

$sql_1 = "SELECT COUNT(*) AS total FROM purchase_bag WHERE `product_name` LIKE '%$query%' AND temp = '0' ORDER BY  `id`,`date`";
$query_1 = $db->query($sql_1);
$row_1 = ($query_1 && ($tmp = $query_1->fetch_assoc())) ? $tmp : ['total' => 0];

$perpage = (int)($pagination['perpage'] ?? 10);
$page = (int)($pagination['page'] ?? 1);
if ($perpage < 1) { $perpage = 10; }
if ($page < 1) { $page = 1; }
$start = ($page - 1) * $perpage;
$pages = $row_1['total'] / $perpage;

$output = array('meta'=> array("page"=> $page, "pages"=> $pages, "perpage"=> $perpage,"total"=> $row_1['total'],"sort"=> 'asc', "field"=> 'SN'), 'data' => array());

$count=1;
$sql = "SELECT * FROM purchase_bag WHERE `product_name` LIKE '%$query%' AND temp = '0' ORDER BY `id`,`date` LIMIT ".$start.','.$perpage;
$query = $db->query($sql);
if ($query) {
while($row = $query->fetch_assoc()){

    if(($_SESSION['userlevel'] ?? '') == 'sadmin_df56fdg'){
        $actionBtn = '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" onclick="addToPurchaseOrder(\''.$row['id'].'\')" title="Add to Purchase">
                            <i class="flaticon2-send-1"></i>
                        </a>
                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="modal" data-target="#delete_item_purchase_bag" title="Delete" onclick="removePurchaseBag(\''.$row['id'].'\')">
                            <i class="flaticon2-trash"></i>
                        </a>';
    }else{
        $actionBtn = '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" onclick="addToPurchaseOrder(\''.$row['id'].'\')" title="Add to Purchase">
                            <i class="flaticon2-send-1"></i>
                        </a>
                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="modal" data-target="#delete_item_purchase_bag" title="Delete" onclick="removePurchaseBag(\''.$row['id'].'\')">
                            <i class="flaticon2-trash"></i>
                        </a>';
    }
    
    $product_name = $row['product_name'];
    $sql_temp = "SELECT * FROM product WHERE `name` = '$product_name'";
    $query_temp = $db->query($sql_temp);
    $row_temp = ($query_temp && ($tmp = $query_temp->fetch_assoc())) ? $tmp : [];

    $output['data'][] = array(      
            'RecordID' => $row['id'],
            'Name' => $row['product_name'],
            'Group' => $row_temp['group'] ?? '',
            'Category' => $row_temp['category'] ?? '',
            'Sub_Category' => $row_temp['sub_category'] ?? '',
            'Quantity'=>$row['quantity'],
            'Date' => !empty($row['date']) ? date('d-m-Y', strtotime($row['date'])) : '',
            'User'=>$row['log_user'],
            'Actions' => $actionBtn
    );
}
}
echo json_encode($output);
?>
