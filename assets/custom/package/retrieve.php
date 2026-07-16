<?php
session_start();
require_once "../connect.php";

$pagination = $_REQUEST['pagination'] ?? [];
$query_array = $_REQUEST['query'] ?? [];
$sort_array = $_REQUEST['sort'] ?? [];

$query = $query_array['generalSearch'] ?? '';

$user_level = $_SESSION['userlevel'] ?? '';
$user = $_SESSION['user'] ?? '';

$sql_1 = "SELECT COUNT(*) AS total FROM im_package";
$query_1 = $db->query($sql_1);
$row_1 = ($query_1 && ($tmp = $query_1->fetch_assoc())) ? $tmp : ['total' => 0];

$perpage = (int)($pagination['perpage'] ?? 10);
$page = (int)($pagination['page'] ?? 1);
if ($perpage < 1) { $perpage = 10; }
if ($page < 1) { $page = 1; }
$start = ($page - 1) * $perpage;
$pages = $row_1['total'] / $perpage;

$output = array('meta'=> array("page"=> $page, "pages"=> $pages, "perpage"=> $perpage,"total"=> $row_1['total'],"sort"=> 'asc', "field"=> 'SN'), 'data' => array());

if($user_level == 'im_admin_BBbdjnjdshS'){
  $sql = "SELECT * FROM im_package ORDER BY name LIMIT ".$start.','.$perpage ;
}else{
  $sql = "SELECT * FROM im_package WHERE name IN (SELECT package FROM `im_order` WHERE `users` LIKE '%$user%') OR added_by = '$user' ORDER BY name LIMIT ".$start.','.$perpage;
}

$count=1;
$query = $db->query($sql);

if ($query) {
while($row = $query->fetch_assoc()){
	$actionBtn = '<div class="dropdown"><a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown"><i class="flaticon-more-1"></i></a><div class="dropdown-menu dropdown-menu-right">
    <ul class="kt-nav">
        <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#kt_modal_e_quotation" onclick="editQuotation(\''.$row['id'].'\')" title="Edit Quotation Order"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-contract"></i><span class="kt-nav__link-text">Edit</span></a></li>
        <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#kt_modal_d_quotation" title="Delete" onclick="removeQuotation(\''.$row['id'].'\')"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-trash"></i><span class="kt-nav__link-text">Delete</span></a></li>
        <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#kt_modal_ai_quotation" onclick="addItemQuotation(\''.$row['id'].'\')" class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-add-square"></i><span class="kt-nav__link-text">Add Item</span></a></li>
    </ul>
    </div></div>';

    $output['data'][] = array(
		        'SN' => $count++,
                'Name' => $row['name'],
                'Currency' => $row['currency'],
                'Conversion'=>$row['conversion'],
                'Actions' => $actionBtn
	);
}
}
echo json_encode($output);
