<?php
session_start();
require_once "../connect.php";
setlocale(LC_MONETARY, 'en_IN');

$date_start = $_SESSION['start'] ?? '';
$date_end = $_SESSION['end'] ?? '';

$start_year = $date_start !== '' ? date('Y', strtotime($date_start)) : date('Y');
$end_year = $date_end !== '' ? date('Y', strtotime($date_end)) : date('Y');
$year = $start_year . '-' . substr($date_end !== '' ? $date_end : $end_year, 2, 2);

$pagination = $_REQUEST['pagination'] ?? ['page' => 1, 'perpage' => 10];
$query_array = $_REQUEST['query'] ?? [];
$sort_array = $_REQUEST['sort'] ?? [];

$query = str_replace([" ", "-"], "", $query_array['generalSearch'] ?? '');
$group = $query_array['group'] ?? '';
$category = $query_array['category'] ?? '';
$sub_category = $query_array['sub_category'] ?? '';

if ($sub_category == '') {
    $sub_category = '%';
    $_SESSION['sub_category'] = '%';
} else {
    $_SESSION['sub_category'] = $sub_category;
}

if ($category == '') {
    $category = '%';
    $_SESSION['category'] = '%';
} else {
    $_SESSION['category'] = $category;
}

if ($group == '') {
    $group = '%';
    $_SESSION['group'] = '%';
} else {
    $_SESSION['group'] = $group;
}

$perpage = (int)($pagination['perpage'] ?? 10);
$page = (int)($pagination['page'] ?? 1);
if ($perpage < 1) { $perpage = 10; }
if ($page < 1) { $page = 1; }
$start = ($page - 1) * $perpage;

$sql_1 = "SELECT COUNT(*) AS total FROM product WHERE (REPLACE(REPLACE(`name`, ' ', ''), '-', '') LIKE '%$query%' || REPLACE(REPLACE(`description`, ' ', ''), '-', '') LIKE '%$query%' || REPLACE(REPLACE(`aliases`, ' ', ''), '-', '') LIKE '%$query%' || REPLACE(REPLACE(`hsn`, ' ', ''), '-', '') LIKE '%$query%') AND `group` LIKE '$group' AND `category` LIKE '$category' AND `sub_category` LIKE '$sub_category' AND `archive` = '1'";
$query_1 = $db->query($sql_1);
$row_1 = ($query_1) ? $query_1->fetch_assoc() : null;
$total = (int)($row_1['total'] ?? 0);
$pages = $perpage > 0 ? $total / $perpage : 0;

$output = array('meta' => array("page" => $page, "pages" => $pages, "perpage" => $perpage, "total" => $total, "sort" => 'asc', "field" => 'SN'), 'data' => array());

$count = 1;
$sql = "SELECT * FROM product WHERE (REPLACE(REPLACE(`name`, ' ', ''), '-', '') LIKE '%$query%' || REPLACE(REPLACE(`description`, ' ', ''), '-', '') LIKE '%$query%' || REPLACE(REPLACE(`aliases`, ' ', ''), '-', '') LIKE '%$query%' || REPLACE(REPLACE(`hsn`, ' ', ''), '-', '') LIKE '%$query%') AND `group` LIKE '$group' AND `category` LIKE '$category' AND `sub_category` LIKE '$sub_category' AND `archive` = '1' ORDER BY `group`,`name` LIMIT " . $start . ',' . $perpage;
$query = $db->query($sql);

if ($query) {
while ($row = $query->fetch_assoc()) {
    $name = $row['name'] ?? '';
    $row_id = $row['id'] ?? '';

    $actionBtn = '<div class="dropdown"><a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown">
                    <i class="flaticon-more-1"></i></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <ul class="kt-nav">
                            <li class="kt-nav__item">
                                <a data-toggle="modal" data-target="#kt_modal_add_purchase_bag" class="kt-nav__link" onclick="PurchaseBagLoad(\'' . htmlspecialchars($name, ENT_QUOTES) . '\')"><i class="kt-nav__link-icon flaticon2-send-1"  ></i><span class="kt-nav__link-text">Add to bag</span></a>
                            </li>
                            <li class="kt-nav__item">
                                <a class="kt-nav__link" onclick="updated_stock_toggle(\'' . $row_id . '\')"><i class="kt-nav__link-icon flaticon2-graph-1"  ></i><span class="kt-nav__link-text">Updated Stock Toggle</span></a>
                            </li>
                            <li class="kt-nav__item">
                                <a class="kt-nav__link" onclick="archive_product(\'' . $row_id . '\')"><i class="kt-nav__link-icon flaticon2-cross"  ></i><span class="kt-nav__link-text">Archive</span></a>
                            </li>
                        </ul>
                    </div>
                </div>';

    $opening_stock = 0;
    $new_opening_stock = json_decode($row['new_opening_stock'] ?? '', true);
    if (is_array($new_opening_stock) && isset($new_opening_stock['year']) && is_array($new_opening_stock['year'])) {
        $len = count($new_opening_stock['year']);
        for ($i = 0; $i < $len; $i++) {
            if (($new_opening_stock['year'][$i] ?? '') == $year) {
                $opening_stock = (float)($new_opening_stock['stock'][$i] ?? 0);
            }
        }
    }
    $stock = $opening_stock;

    // Sales
    $sql_tmp = "SELECT * FROM sales_invoice WHERE items LIKE '%$name%' AND `si_date` BETWEEN '$date_start' AND '$date_end'";
    $query_tmp = $db->query($sql_tmp);
    if ($query_tmp) {
        while ($row_tmp = $query_tmp->fetch_assoc()) {
            $items = json_decode($row_tmp['items'] ?? '', true);
            if (is_array($items) && isset($items['product']) && is_array($items['product'])) {
                $len = count($items['product']);
                for ($i = 0; $i < $len; $i++) {
                    if (($items['product'][$i] ?? '') == $name) {
                        if (($row_tmp['series'] ?? '') == 'SECONDARY') {
                            $stock -= (float)($items['effective_quantity'][$i] ?? 0);
                        } else {
                            $stock -= (float)($items['quantity'][$i] ?? 0);
                        }
                    }
                }
            }
        }
    }

    // Sales Order
    $sql_tmp = "SELECT * FROM sales_order WHERE items LIKE '%$name%' AND collected = '1' AND `status` = '0' AND `so_date` BETWEEN '$date_start' AND '$date_end'";
    $query_tmp = $db->query($sql_tmp);
    if ($query_tmp) {
        while ($row_tmp = $query_tmp->fetch_assoc()) {
            $items = json_decode($row_tmp['items'] ?? '', true);
            if (is_array($items) && isset($items['product']) && is_array($items['product'])) {
                $len = count($items['product']);
                for ($i = 0; $i < $len; $i++) {
                    if (($items['product'][$i] ?? '') == $name) {
                        $stock -= (float)($items['quantity'][$i] ?? 0);
                    }
                }
            }
        }
    }

    // Purchase
    $sql_tmp = "SELECT * FROM purchase_invoice WHERE items LIKE '%$name%' AND `pi_date` BETWEEN '$date_start' AND '$date_end'";
    $query_tmp = $db->query($sql_tmp);
    if ($query_tmp) {
        while ($row_tmp = $query_tmp->fetch_assoc()) {
            $items = json_decode($row_tmp['items'] ?? '', true);
            if (is_array($items) && isset($items['product']) && is_array($items['product'])) {
                $len = count($items['product']);
                for ($i = 0; $i < $len; $i++) {
                    if (($items['product'][$i] ?? '') == $name) {
                        $stock += (float)($items['quantity'][$i] ?? 0);
                    }
                }
            }
        }
    }

    $sql_tmp = "SELECT * FROM credit_note WHERE items LIKE '%$name%' AND `cn_date` BETWEEN '$date_start' AND '$date_end'";
    $query_tmp = $db->query($sql_tmp);
    if ($query_tmp) {
        while ($row_tmp = $query_tmp->fetch_assoc()) {
            $items = json_decode($row_tmp['items'] ?? '', true);
            if (is_array($items) && isset($items['product']) && is_array($items['product'])) {
                $len = count($items['product']);
                for ($i = 0; $i < $len; $i++) {
                    if (($items['product'][$i] ?? '') == $name) {
                        $stock += (float)($items['quantity'][$i] ?? 0);
                    }
                }
            }
        }
    }

    $sql_tmp = "SELECT * FROM debit_note WHERE items LIKE '%$name%' AND `dn_date` BETWEEN '$date_start' AND '$date_end'";
    $query_tmp = $db->query($sql_tmp);
    if ($query_tmp) {
        while ($row_tmp = $query_tmp->fetch_assoc()) {
            $items = json_decode($row_tmp['items'] ?? '', true);
            if (is_array($items) && isset($items['product']) && is_array($items['product'])) {
                $len = count($items['product']);
                for ($i = 0; $i < $len; $i++) {
                    if (($items['product'][$i] ?? '') == $name) {
                        $stock -= (float)($items['quantity'][$i] ?? 0);
                    }
                }
            }
        }
    }

    $pr_search = "\"" . $name . "\"";

    // Assemblies
    $sql_tmp = "SELECT * FROM assembly_operation WHERE composite = '$name' AND `operation` = 'Assembled' AND `log_date` BETWEEN '$date_start' AND '$date_end'";
    $query_tmp = $db->query($sql_tmp);
    if ($query_tmp) {
        while ($row_tmp = $query_tmp->fetch_assoc()) {
            $stock += (float)($row_tmp['quantity'] ?? 0);
        }
    }

    $sql_tmp = "SELECT * FROM assembly_operation WHERE items LIKE '%$pr_search%' AND `operation` = 'Assembled' AND `log_date` BETWEEN '$date_start' AND '$date_end'";
    $query_tmp = $db->query($sql_tmp);
    if ($query_tmp) {
        while ($row_tmp = $query_tmp->fetch_assoc()) {
            $items = json_decode($row_tmp['items'] ?? '', true);
            if (is_array($items) && isset($items['product']) && is_array($items['product'])) {
                $len = count($items['product']);
                for ($i = 0; $i < $len; $i++) {
                    if (($items['product'][$i] ?? '') == $name) {
                        $qty = (float)($row_tmp['quantity'] ?? 0) * (float)($items['quantity'][$i] ?? 0);
                        $stock -= $qty;
                    }
                }
            }
        }
    }

    // Disassemble
    $sql_tmp = "SELECT * FROM assembly_operation WHERE composite = '$name' AND `operation` = 'Disassembled' AND `log_date` BETWEEN '$date_start' AND '$date_end'";
    $query_tmp = $db->query($sql_tmp);
    if ($query_tmp) {
        while ($row_tmp = $query_tmp->fetch_assoc()) {
            $stock -= (float)($row_tmp['quantity'] ?? 0);
        }
    }

    $sql_tmp = "SELECT * FROM assembly_operation WHERE items LIKE '%$pr_search%' AND `operation` = 'Disassembled' AND `log_date` BETWEEN '$date_start' AND '$date_end'";
    $query_tmp = $db->query($sql_tmp);
    if ($query_tmp) {
        while ($row_tmp = $query_tmp->fetch_assoc()) {
            $items = json_decode($row_tmp['items'] ?? '', true);
            if (is_array($items) && isset($items['product']) && is_array($items['product'])) {
                $len = count($items['product']);
                for ($i = 0; $i < $len; $i++) {
                    if (($items['product'][$i] ?? '') == $name) {
                        $qty = (float)($row_tmp['quantity'] ?? 0) * (float)($items['quantity'][$i] ?? 0);
                        $stock += $qty;
                    }
                }
            }
        }
    }

    $url = '<strong><a href="?page=product_details&pr=' . urlencode($name) . '" target="_blank">' . strtoupper((string)$name) . '</a></strong>';

    $output['data'][] = array(
        'SN' => $count++,
        'Name' => $url,
        'Description' => $row['description'] ?? '',
        'Alias' => $row['aliases'] ?? '',
        'Updated_Stock' => $row['updated_stock'] ?? '',
        'Updated_Price' => $row['updated_price'] ?? '',
        'Group' => strtoupper((string)($row['group'] ?? '')),
        'Category' => strtoupper((string)($row['category'] ?? '')),
        'Sub-Category' => strtoupper((string)($row['sub_category'] ?? '')),
        'Unit' => strtoupper((string)($row['unit'] ?? '')),
        'Cost' => number_format((float)($row['cost'] ?? 0), 2),
        'Rate' => number_format((float)($row['rate'] ?? 0), 2),
        'Tax' => $row['tax'] ?? '',
        'HSN' => $row['hsn'] ?? '',
        'Opening_stock' => round($stock, 2),
        'Actions' => $actionBtn
    );
}
}

echo json_encode($output);

?>
