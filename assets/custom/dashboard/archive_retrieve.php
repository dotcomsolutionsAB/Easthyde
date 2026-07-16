<?php
session_start();
require_once "../connect.php";
setlocale(LC_MONETARY, 'en_IN');

$date_start = $_SESSION['start'];
$date_end = $_SESSION['end'];

$start_year = date('Y', strtotime($date_start));
$end_year = date('Y', strtotime($date_end));

$year = $start_year.'-'.substr($date_end, 2,2);

$pagination = $_REQUEST['pagination'];  
$query_array = $_REQUEST['query'];  
$sort_array = $_REQUEST['sort'];  

$query = $query_array['generalSearch'];
$query=str_replace(" ","",$query);
$query=str_replace("-","",$query);
$group = $query_array['group'];
$category = $query_array['category'];
$sub_category = $query_array['sub_category'];

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

$sql_1 = "SELECT COUNT(*) AS total FROM product WHERE (REPLACE(REPLACE(`name`, ' ', ''), '-', '') LIKE '%$query%' || REPLACE(REPLACE(`description`, ' ', ''), '-', '') LIKE '%$query%' || REPLACE(REPLACE(`aliases`, ' ', ''), '-', '') LIKE '%$query%' || REPLACE(REPLACE(`hsn`, ' ', ''), '-', '') LIKE '%$query%') AND `group` LIKE '$group' AND `category` LIKE '$category' AND `sub_category` LIKE '$sub_category' AND `archive` = '1'";
$query_1 = $db->query($sql_1);
$row_1 = $query_1->fetch_assoc();

$perpage = $pagination['perpage'];
$start = ($pagination['page']-1)*$perpage;
$pages = $row_1['total'] / $perpage;

$output = array('meta'=> array("page"=> $pagination['page'], "pages"=> $pages, "perpage"=> $perpage,"total"=> $row_1['total'],"sort"=> 'asc', "field"=> 'SN'), 'data' => array());

$count=1;
$sql = "SELECT * FROM product WHERE (REPLACE(REPLACE(`name`, ' ', ''), '-', '') LIKE '%$query%' || REPLACE(REPLACE(`description`, ' ', ''), '-', '') LIKE '%$query%' || REPLACE(REPLACE(`aliases`, ' ', ''), '-', '') LIKE '%$query%' || REPLACE(REPLACE(`hsn`, ' ', ''), '-', '') LIKE '%$query%') AND `group` LIKE '$group' AND `category` LIKE '$category' AND `sub_category` LIKE '$sub_category' AND `archive` = '1' ORDER BY `group`,`name` LIMIT ".$start.','.$perpage;
$query = $db->query($sql);
while($row = $query->fetch_assoc()){
    $name=$row['name'];

    $row_id = $row['id'];

    $actionBtn = '<div class="dropdown"><a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown">
                    <i class="flaticon-more-1"></i></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <ul class="kt-nav">
                            <li class="kt-nav__item">
                                <a data-toggle="modal" data-target="#kt_modal_add_purchase_bag" class="kt-nav__link" onclick="PurchaseBagLoad(\''.$name.'\')"><i class="kt-nav__link-icon flaticon2-send-1"  ></i><span class="kt-nav__link-text">Add to bag</span></a>
                            </li>
                            <li class="kt-nav__item">
                                <a class="kt-nav__link" onclick="updated_stock_toggle(\''.$row_id.'\')"><i class="kt-nav__link-icon flaticon2-graph-1"  ></i><span class="kt-nav__link-text">Updated Stock Toggle</span></a>
                            </li>
                            <li class="kt-nav__item">
                                <a class="kt-nav__link" onclick="archive_product(\''.$row_id.'\')"><i class="kt-nav__link-icon flaticon2-cross"  ></i><span class="kt-nav__link-text">Archive</span></a>
                            </li>
                        </ul>
                    </div>
                </div>';
    
    $sql_year = "SELECT * FROM year WHERE current = '1'";
    $query_year = $db->query($sql_year);
    $row_year = $query_year->fetch_assoc();

    // $year = $row_year['year'];
    // $start = $row_year['start'];
    // $end = $row_year['end'];

    $new_opening_stock = json_decode($row['new_opening_stock'],true);
    $len = sizeof($new_opening_stock['year']);
    // echo $new_opening_stock['year'][1];

    for($i=0;$i<$len;$i++)
    {
        if($new_opening_stock['year'][$i] == $year)
        {
            $opening_stock = $new_opening_stock['stock'][$i];
        }
    }
    // $opening_stock = $row_pr['opening_stock'];
    $stock = $opening_stock;

    // $stock = $row['opening_stock'];

    // Sales
    $sql_tmp = "SELECT * FROM sales_invoice WHERE items LIKE '%$name%' AND `si_date` BETWEEN '$date_start' AND '$date_end'";
    $query_tmp = $db->query($sql_tmp);
    while($row_tmp = $query_tmp->fetch_assoc()){
        $items = json_decode($row_tmp['items'], true);
        $len = sizeof($items['product']);
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

    // Sales
    $sql_tmp = "SELECT * FROM sales_order WHERE items LIKE '%$name%' AND collected = '1' AND `status` = '0' AND `so_date` BETWEEN '$date_start' AND '$date_end'";
    $query_tmp = $db->query($sql_tmp);
    while($row_tmp = $query_tmp->fetch_assoc()){
        $items = json_decode($row_tmp['items'], true);
        $len = sizeof($items['product']);
        for($i=0;$i<$len;$i++){
            if($items['product'][$i] == $name)
            {
                $stock -= $items['quantity'][$i];
            }
        }
    }

    // Purchase
    $sql_tmp = "SELECT * FROM purchase_invoice WHERE items LIKE '%$name%' AND `pi_date` BETWEEN '$date_start' AND '$date_end'";
    $query_tmp = $db->query($sql_tmp);
    while($row_tmp = $query_tmp->fetch_assoc()){
        $items = json_decode($row_tmp['items'], true);
        $len = sizeof($items['product']);
        for($i=0;$i<$len;$i++){
            if($items['product'][$i] == $name)
            {
                $stock += $items['quantity'][$i];
            }
        }
    }

    $sql_tmp = "SELECT * FROM credit_note WHERE items LIKE '%$name%' AND `cn_date` BETWEEN '$date_start' AND '$date_end'";
    $query_tmp = $db->query($sql_tmp);
    while($row_tmp = $query_tmp->fetch_assoc()){
        $items = json_decode($row_tmp['items'], true);
        $len = sizeof($items['product']);
        for($i=0;$i<$len;$i++){
            if($items['product'][$i] == $name)
            {
                $stock += $items['quantity'][$i];
            }
        }
    }

    $sql_tmp = "SELECT * FROM debit_note WHERE items LIKE '%$name%' AND `dn_date` BETWEEN '$date_start' AND '$date_end'";
    $query_tmp = $db->query($sql_tmp);
    while($row_tmp = $query_tmp->fetch_assoc()){
        $items = json_decode($row_tmp['items'], true);
        $len = sizeof($items['product']);
        for($i=0;$i<$len;$i++){
            if($items['product'][$i] == $name)
            {
                $stock -= $items['quantity'][$i];
            }
        }
    }

    $pr_search="\"".$name."\"";

    // Assemblies
    $sql_tmp = "SELECT * FROM assembly_operation WHERE composite = '$name' AND `operation` = 'Assembled' AND `log_date` BETWEEN '$date_start' AND '$date_end'";
    $query_tmp = $db->query($sql_tmp);
    while($row_tmp = $query_tmp->fetch_assoc()){
        $stock += $row_tmp['quantity'];
    }

    $sql_tmp = "SELECT * FROM assembly_operation WHERE items LIKE '%$pr_search%' AND `operation` = 'Assembled' AND `log_date` BETWEEN '$date_start' AND '$date_end'";
    $query_tmp = $db->query($sql_tmp);
    while($row_tmp = $query_tmp->fetch_assoc()){
        $items = json_decode($row_tmp['items'], true);
        $len = sizeof($items['product']);
        for($i=0;$i<$len;$i++){
            if($items['product'][$i] == $name)
            {
                $qty = $row_tmp['quantity'] * $items['quantity'][$i];
                $stock -= $qty;
            }
        }
    }

    // Disassemble
    $sql_tmp = "SELECT * FROM assembly_operation WHERE composite = '$name' AND `operation` = 'Disassembled' AND `log_date` BETWEEN '$date_start' AND '$date_end'";
    $query_tmp = $db->query($sql_tmp);
    while($row_tmp = $query_tmp->fetch_assoc()){
        $stock -= $row_tmp['quantity'];
    }

    $sql_tmp = "SELECT * FROM assembly_operation WHERE items LIKE '%$pr_search%' AND `operation` = 'Disassembled' AND `log_date` BETWEEN '$date_start' AND '$date_end'";
    $query_tmp = $db->query($sql_tmp);
    while($row_tmp = $query_tmp->fetch_assoc()){
        $items = json_decode($row_tmp['items'], true);
        $len = sizeof($items['product']);
        for($i=0;$i<$len;$i++){
            if($items['product'][$i] == $name)
            {
                $qty = $row_tmp['quantity'] * $items['quantity'][$i];
                $stock += $qty;
            }
        }
    }



    // $url = "<a href='?page=product_details&pr=".$row['name']."' target='_blank'>".strtoupper($row['name'])."</a>";
    $url = '<strong><a href="?page=product_details&pr='.urlencode($row['name']).'" target="_blank">'.strtoupper($row['name']).'</a></strong>';
    // $url = strtoupper($row['name']);

    

    $output['data'][] = array(      
            'SN' => $count++,
            'Name' => $url,
            'Description' => $row['description'],
            // 'Description' => $len,
            'Alias' => $row['aliases'],
            'Updated_Stock' => $row['updated_stock'],
            'Updated_Price' => $row['updated_price'],
            'Group' => strtoupper($row['group']),
            'Category' => strtoupper($row['category']),
            'Sub-Category' => strtoupper($row['sub_category']),
            'Unit' => strtoupper($row['unit']),
            'Cost' => number_format($row['cost'],2),
            'Rate' => number_format($row['rate'],2),
            'Tax' => $row['tax'],
            'HSN' => $row['hsn'],
            'Opening_stock' => round($stock,2),
            'Actions' => $actionBtn
    );
}

echo json_encode($output);

?>