<?php
session_start();
require_once "../connect.php";
setlocale(LC_MONETARY, 'en_IN');

$pagination = $_REQUEST['pagination'] ?? [];
$query_array = $_REQUEST['query'] ?? [];
$sort_array = $_REQUEST['sort'] ?? [];

$query = $query_array['generalSearch'] ?? '';
$query=str_replace(" ","",$query);
$query=str_replace("-","",$query);
$group = $query_array['group'] ?? '';
$category = $query_array['category'] ?? '';
$sub_category = $query_array['sub_category'] ?? '';

if($sub_category == '' ){
    $sub_category = '%';
    $_SESSION['sub_category'] = '%';
}else{
    $_SESSION['sub_category'] = $sub_category;
}

if($category == '' ){
    $category = '%';
    $_SESSION['category'] = '%';
}else{
    $_SESSION['category'] = $category;
}

if($group == ''){
    $group = '%';
    $_SESSION['group'] = '%';
}else{
    $_SESSION['group'] = $group;
}

$result = [];
$count=1;
$sql = "SELECT * FROM product WHERE (REPLACE(REPLACE(`name`, ' ', ''), '-', '') LIKE '%$query%' || REPLACE(REPLACE(`description`, ' ', ''), '-', '') LIKE '%$query%' || REPLACE(REPLACE(`aliases`, ' ', ''), '-', '') LIKE '%$query%') AND `group` LIKE '$group' AND `category` LIKE '$category' AND `sub_category` LIKE '$sub_category' ORDER BY `group`,`name`";
$query = $db->query($sql);
if ($query) {
while($row = $query->fetch_assoc()){
    $name=$row['name'];

    $actionBtn = '<div class="dropdown"><a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown">
                    <i class="flaticon-more-1"></i></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <ul class="kt-nav">
                            <li class="kt-nav__item">
                                <a data-toggle="modal" data-target="#kt_modal_add_purchase_bag" class="kt-nav__link" onclick="PurchaseBagLoad(\''.$name.'\')"><i class="kt-nav__link-icon flaticon2-send-1"  ></i><span class="kt-nav__link-text">Add to bag</span></a>
                            </li>
                        </ul>
                    </div>
                </div>';

    $stock = $row['opening_stock'];

    // Sales
    $sql_tmp = "SELECT * FROM sales_invoice WHERE items LIKE '%$name%'";
    $query_tmp = $db->query($sql_tmp);
    if ($query_tmp) {
    while($row_tmp = $query_tmp->fetch_assoc()){
        $items = json_decode($row_tmp['items'] ?? '', true);
        $len = (is_array($items) && isset($items['product']) && is_array($items['product'])) ? sizeof($items['product']) : 0;
        for($i=0;$i<$len;$i++){
            if($items['product'][$i] == $name)
            {
                if($row_tmp['series'] == 'SECONDARY' ){
                    $stock -= $items['effective_quantity'][$i];
                }else{
                    $stock -= $items['quantity'][$i];
                }
            }
        }
    }
    }

    // Sales
    $sql_tmp = "SELECT * FROM sales_order WHERE items LIKE '%$name%' AND collected = '1' AND `status` = '0'";
    $query_tmp = $db->query($sql_tmp);
    if ($query_tmp) {
    while($row_tmp = $query_tmp->fetch_assoc()){
        $items = json_decode($row_tmp['items'] ?? '', true);
        $len = (is_array($items) && isset($items['product']) && is_array($items['product'])) ? sizeof($items['product']) : 0;
        for($i=0;$i<$len;$i++){
            if($items['product'][$i] == $name)
            {
                $stock -= $items['quantity'][$i];
            }
        }
    }
    }

    // Purchase
    $sql_tmp = "SELECT * FROM purchase_invoice WHERE items LIKE '%$name%'";
    $query_tmp = $db->query($sql_tmp);
    if ($query_tmp) {
    while($row_tmp = $query_tmp->fetch_assoc()){
        $items = json_decode($row_tmp['items'] ?? '', true);
        $len = (is_array($items) && isset($items['product']) && is_array($items['product'])) ? sizeof($items['product']) : 0;
        for($i=0;$i<$len;$i++){
            if($items['product'][$i] == $name)
            {
                $stock += $items['quantity'][$i];
            }
        }
    }
    }

    $pr_search="\"".$name."\"";

    // Assemblies
    $sql_tmp = "SELECT * FROM assembly_operation WHERE composite = '$name' AND `operation` = 'Assembled'";
    $query_tmp = $db->query($sql_tmp);
    if ($query_tmp) {
    while($row_tmp = $query_tmp->fetch_assoc()){
        $stock += $row_tmp['quantity'];
    }
    }

    $sql_tmp = "SELECT * FROM assembly_operation WHERE items LIKE '%$pr_search%' AND `operation` = 'Assembled'";
    $query_tmp = $db->query($sql_tmp);
    if ($query_tmp) {
    while($row_tmp = $query_tmp->fetch_assoc()){
        $items = json_decode($row_tmp['items'] ?? '', true);
        $len = (is_array($items) && isset($items['product']) && is_array($items['product'])) ? sizeof($items['product']) : 0;
        for($i=0;$i<$len;$i++){
            if($items['product'][$i] == $name)
            {
                $qty = $row_tmp['quantity'] * $items['quantity'][$i];
                $stock -= $qty;
            }
        }
    }
    }

    // Disassemble
    $sql_tmp = "SELECT * FROM assembly_operation WHERE composite = '$name' AND `operation` = 'Disassembled'";
    $query_tmp = $db->query($sql_tmp);
    if ($query_tmp) {
    while($row_tmp = $query_tmp->fetch_assoc()){
        $stock -= $row_tmp['quantity'];
    }
    }

    $sql_tmp = "SELECT * FROM assembly_operation WHERE items LIKE '%$pr_search%' AND `operation` = 'Disassembled'";
    $query_tmp = $db->query($sql_tmp);
    if ($query_tmp) {
    while($row_tmp = $query_tmp->fetch_assoc()){
        $items = json_decode($row_tmp['items'] ?? '', true);
        $len = (is_array($items) && isset($items['product']) && is_array($items['product'])) ? sizeof($items['product']) : 0;
        for($i=0;$i<$len;$i++){
            if($items['product'][$i] == $name)
            {
                $qty = $row_tmp['quantity'] * $items['quantity'][$i];
                $stock += $qty;
            }
        }
    }
    }

    $url = '<strong><a href="?page=product_details&pr='.urlencode($row['name']).'" target="_blank">'.strtoupper($row['name']).'</a></strong>';

    $moq = $row['moq'];
	
	if((string)($moq ?? '') !== '' && (string)$moq !== '0')
    {
        if($stock < $moq)
        {
            $result[] = array(      
                $count++,
                $url,
                strtoupper($row['group']),
                strtoupper($row['category']),
                strtoupper($row['sub_category']),
                number_format((float)$row['rate'], 2),
                $row['hsn'],
                $stock,
                $moq,
                $actionBtn
            );
        }
    }
}
}



// $perpage = $pagination['perpage'];
// $start = ($pagination['page']-1)*$perpage;
// $len = sizeof($result);

// $pages = $len / $perpage;

// $end = $start + $perpage;

// for($j=$start;$j<$end;$j++)
// {
//     $result_final[]=$result[$j];
// }


$output = array('data' => $result);

echo json_encode($output);

?>
