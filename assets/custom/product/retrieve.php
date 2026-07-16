<?php
session_start();
require_once "../connect.php";
setlocale(LC_MONETARY, 'en_IN');

$pagination = $_REQUEST['pagination'] ?? [];
$query_array = $_REQUEST['query'] ?? [];
$sort_array = $_REQUEST['sort'] ?? [];

$query = $query_array['generalSearch'] ?? '';
$query=str_replace(" ","",$query);
$query=str_replace(".","",$query);
$query=str_replace("-","",$query);
$group = $query_array['group'] ?? '';
$category = $query_array['category'] ?? '';
$sub_category = $query_array['sub_category'] ?? '';
$vendor = $query_array['vendor'] ?? '';

$archive = $query_array['archive'] ?? '';
if($archive == ''){
    $archive = '%';
}

// if($sub_category == '' || $_SESSION['pr_group'] != $group || $_SESSION['pr_category'] != $category){
//     $sub_category = '%';
//     $_SESSION['pr_sub_category'] = '%';
// }else{
//     $_SESSION['pr_sub_category'] = $sub_category;
// }

// if($category == '' || $_SESSION['pr_group'] != $group){
//     $category = '%';
//     $_SESSION['pr_category'] = '%';
// }else{
//     $_SESSION['pr_category'] = $category;
// }

// if($group == ''){
//     $group = '%';
//     $_SESSION['pr_group'] = '%';
// }else{
//     $_SESSION['pr_group'] = $group;
// }

if($group == ''){
    $group = '%';
}
if($category == ''){
    $category = '%';
}
if($sub_category == ''){
    $sub_category = '%';
}
if($vendor == ''){
    $vendor = '%';
}
$sql_1 = "SELECT COUNT(*) AS total FROM product WHERE (REPLACE(REPLACE(REPLACE(`name`, '.', ''), ' ', ''), '-', '')LIKE '%$query%' || REPLACE(REPLACE(`description`, ' ', ''), '-', '') LIKE '%$query%' || REPLACE(REPLACE(`aliases`, ' ', ''), '-', '') LIKE '%$query%') AND `group` LIKE '$group' AND `category` LIKE '$category' AND `sub_category` LIKE '$sub_category' AND `archive` LIKE '$archive'AND `vendor` LIKE '$vendor'";
$query_1 = $db->query($sql_1);
$row_1 = ($query_1 && ($tmp = $query_1->fetch_assoc())) ? $tmp : ['total' => 0];

$perpage = (int)($pagination['perpage'] ?? 10);
$page = (int)($pagination['page'] ?? 1);
if ($perpage != -1) {
    if ($perpage < 1) { $perpage = 10; }
} else {
    $perpage = (int)$row_1['total'];
}
if ($page < 1) { $page = 1; }
$start = ($page - 1) * $perpage;
$pages = $row_1['total'] / $perpage;

$output = array('meta'=> array("page"=> $page, "pages"=> $pages, "perpage"=> $perpage,"total"=> $row_1['total'],"sort"=> 'asc', "field"=> 'SN'), 'data' => array());

$count=1;
$sql = "SELECT * FROM product WHERE (REPLACE(REPLACE(REPLACE(`name`, '.', ''), ' ', ''), '-', '')LIKE '%$query%' || REPLACE(REPLACE(`description`, ' ', ''), '-', '') LIKE '%$query%' || REPLACE(REPLACE(`aliases`, ' ', ''), '-', '') LIKE '%$query%') AND `group` LIKE '$group' AND `category` LIKE '$category' AND `sub_category` LIKE '$sub_category' AND `archive` LIKE '$archive' AND `vendor` LIKE '$vendor' ORDER BY `name` LIMIT ".$start.','.$perpage;
$query = $db->query($sql);
if ($query) {
while($row = $query->fetch_assoc()){
    
    $username = $_SESSION['username'] ?? '';
    $userlevel = $_SESSION['userlevel'] ?? '';

    $sql_access = "SELECT * FROM users WHERE `username` = '$username'";
    $query_access = $db->query($sql_access);
    $row_access = ($query_access && ($tmp = $query_access->fetch_assoc())) ? $tmp : [];

    $menu_access = json_decode($row_access['access'] ?? '', true);
    if (!is_array($menu_access)) {
        $menu_access = [];
    }
    
    $edit = '';
    $delete = '';

    if(($menu_access['products']['edit'] ?? '') == '1' || $userlevel == "sadmin_df56fdg"){

            $edit = '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="modal" data-target="#kt_modal_e_product" onclick="editProduct(\''.$row['id'].'\')" title="Edit details">
                            <i class="flaticon2-paper"></i>
                        </a>';
    }
    
    if(($menu_access['products']['delete'] ?? '') == '1' || $userlevel == "sadmin_df56fdg"){

            $delete = '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="modal" data-target="#kt_modal_d_product" title="Delete" onclick="removeProduct(\''.$row['id'].'\')">
                            <i class="flaticon2-trash"></i>
                        </a>';
    }

    if(($_SESSION['userlevel'] ?? '') == 'sadmin_df56fdg'){
        $actionBtn = '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="modal" data-target="#kt_modal_e_product" onclick="editProduct(\''.$row['id'].'\')" title="Edit details">
                            <i class="flaticon2-paper"></i>
                        </a>
                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" title="Archive Toggle" onclick="archive_product(\''.$row['id'].'\')">
                            <i class="flaticon2-cross"></i>
                        </a>
                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="modal" data-target="#kt_modal_d_product" title="Delete" onclick="removeProduct(\''.$row['id'].'\')">
                            <i class="flaticon2-trash"></i>
                        </a>';
                        
    }else{
        $actionBtn = $edit.'
                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" title="Archive Toggle" onclick="archive_product(\''.$row['id'].'\')">
                            <i class="flaticon2-cross"></i>
                        </a>
                            ';
    }

    $opening_stock_current = json_decode($row['new_opening_stock'] ?? '', true);
    $len = (is_array($opening_stock_current) && isset($opening_stock_current['year']) && is_array($opening_stock_current['year'])) ? sizeof($opening_stock_current['year']) : 0;

    $sql_year = "SELECT * FROM year WHERE current = '1'";
    $query_year = $db->query($sql_year);
    $row_year = ($query_year && ($tmp = $query_year->fetch_assoc())) ? $tmp : [];

    $year = $row_year['year'] ?? '';
    $opening_stock = '';

    for($i=0;$i<$len;$i++)
    {
        if($opening_stock_current['year'][$i] == $year)
        {
            $opening_stock = $opening_stock_current['stock'][$i];
        }
    }

    if($row['updated_price_date'] == NULL) {
       $updated_price_date = '---'; 
    } else {
        $updated_price_date = date('d-m-Y', strtotime($row['updated_price_date']));
    }

    if($row['updated_cost_date'] == NULL) {
       $updated_cost_date = '---'; 
    } else {
        $updated_cost_date = date('d-m-Y', strtotime($row['updated_cost_date']));
    }

	$output['data'][] = array(		
        	'SN' => $row['name'],
            'Name' => $row['name'],
            'Description' => "",
        	'Group' => $row['group'],
            'Category' => $row['category'],
            'Sub-Category' => $row['sub_category'],
            'Vendor' => $row['vendor'],
            'Updated_Price' => $row['updated_price'],
            'Updated_Price_Date' => $updated_price_date,
            'Updated_Cost' => $row['updated_cost'],
            'Updated_Cost_Date' => $updated_cost_date,
            'Unit' => $row['unit'],
            'Cost' => number_format((float)$row['cost'], 2),
            'Rate' => number_format((float)$row['rate'], 2),
            'Tax' => $row['tax'],
            'HSN' => $row['hsn'],
            'Archive' => $row['archive'],
            'Opening_stock' => $opening_stock,
            'Actions' => $actionBtn
	);
}
}

echo json_encode($output);

?>
