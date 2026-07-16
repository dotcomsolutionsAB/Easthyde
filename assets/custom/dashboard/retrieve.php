<?php
session_start();
//ini_set("display_errors", 1);
require_once "../connect.php";
setlocale(LC_MONETARY, 'en_IN');

// Retrieve date range from session
$date_start = $_SESSION['start'] ?? '';
$date_end = $_SESSION['end'] ?? '';

$start_year = date('Y', strtotime($date_start));
$end_year = date('Y', strtotime($date_end));
$year = $start_year . '-' . substr($date_end, 2, 2);

// Retrieve pagination and query parameters from request
$pagination = $_REQUEST['pagination'] ?? ['page' => 1, 'perpage' => 10];
$query_array = $_REQUEST['query'] ?? [];
$sort_array = $_REQUEST['sort'] ?? [];

$query = str_replace([" ", "-"], "", $query_array['generalSearch'] ?? '');
$group = $query_array['group'] ?? '%';
$category = $query_array['category'] ?? '%';
$sub_category = $query_array['sub_category'] ?? '%';
$positive = $query_array['positive'] ?? 1;

// Set defaults for pagination if not provided
$perpage = (int)($pagination['perpage'] ?? 10);
$page = (int)($pagination['page'] ?? 1);
$start = ($page - 1) * $perpage;

// Initialize the output array
$aoutput = [];
$count = 1;

// Query to get the products based on the filters (before 'positive' filter)
$sql = "SELECT * FROM product WHERE 
        (REPLACE(REPLACE(`name`, ' ', ''), '-', '') LIKE '%$query%' 
        OR REPLACE(REPLACE(`description`, ' ', ''), '-', '') LIKE '%$query%' 
        OR REPLACE(REPLACE(`aliases`, ' ', ''), '-', '') LIKE '%$query%' 
        OR REPLACE(REPLACE(`hsn`, ' ', ''), '-', '') LIKE '%$query%') 
        AND `group` LIKE '$group' 
        AND `category` LIKE '$category' 
        AND `sub_category` LIKE '$sub_category' 
        AND `archive` = '0' 
        ORDER BY `group`, `name`";

$query_result = $db->query($sql);

if (!$query_result) {
    die("Error in query: " . $db->error);
}

// Process each product and calculate stock
while ($row = $query_result->fetch_assoc()) {
    $name = $row['name'];
    $row_id = $row['id'];

    // Initialize stock
    $stock = 0;

    // Get the opening stock for the year
    $new_opening_stock = json_decode($row['new_opening_stock'], true);
    if (is_array($new_opening_stock) && isset($new_opening_stock['year'])) {
        $index = array_search($year, $new_opening_stock['year']);
        if ($index !== false && isset($new_opening_stock['stock'][$index])) {
            $opening_stock = $new_opening_stock['stock'][$index];
        } else {
            $opening_stock = 0;
        }
    } else {
        $opening_stock = 0;
    }
    $stock = $opening_stock;

    // Adjust stock based on transactions
    // Sales Invoice Adjustment
    $sql_sales_invoice = "SELECT * FROM sales_invoice WHERE items LIKE '%$name%' AND `si_date` BETWEEN '$date_start' AND '$date_end'";
    $query_sales_invoice = $db->query($sql_sales_invoice);
    while ($row_sales = $query_sales_invoice->fetch_assoc()) {
        $items = json_decode($row_sales['items'], true);
        if (isset($items['product'])) {
            foreach ($items['product'] as $i => $product_name) {
                if ($product_name == $name) {
                    $quantity = $items['quantity'][$i];
                    $stock -= $quantity;
                }
            }
        }
    }

    // Sales Order Adjustment
    $sql_sales_order = "SELECT * FROM sales_order WHERE items LIKE '%$name%' AND collected = '1' AND `status` = '0' AND `so_date` BETWEEN '$date_start' AND '$date_end'";
    $query_sales_order = $db->query($sql_sales_order);
    while ($row_sales_order = $query_sales_order->fetch_assoc()) {
        $items = json_decode($row_sales_order['items'], true);
        if (isset($items['product'])) {
            foreach ($items['product'] as $i => $product_name) {
                if ($product_name == $name) {
                    $stock -= $items['quantity'][$i];
                }
            }
        }
    }

    // Purchase Invoice Adjustment
    $sql_purchase = "SELECT * FROM purchase_invoice WHERE items LIKE '%$name%' AND `pi_date` BETWEEN '$date_start' AND '$date_end'";
    $query_purchase = $db->query($sql_purchase);
    while ($row_purchase = $query_purchase->fetch_assoc()) {
        $items = json_decode($row_purchase['items'], true);
        if (isset($items['product'])) {
            foreach ($items['product'] as $i => $product_name) {
                if ($product_name == $name) {
                    $stock += $items['quantity'][$i];
                }
            }
        }
    }

    

    

    // Assembly Operations
    // Assembled
    $sql_assembled = "SELECT * FROM assembly_operation WHERE composite = '$name' AND `operation` = 'Assembled' AND `log_date` BETWEEN '$date_start' AND '$date_end'";
    $query_assembled = $db->query($sql_assembled);
    while ($row_assembled = $query_assembled->fetch_assoc()) {
        $stock += $row_assembled['quantity'];
    }

    // Disassembled
    $sql_disassembled = "SELECT * FROM assembly_operation WHERE composite = '$name' AND `operation` = 'Disassembled' AND `log_date` BETWEEN '$date_start' AND '$date_end'";
    $query_disassembled = $db->query($sql_disassembled);
    while ($row_disassembled = $query_disassembled->fetch_assoc()) {
        $stock -= $row_disassembled['quantity'];
    }

    // Apply the 'positive' filter
    $include_product = false;
    if ($positive == 1) {
        // Include all products
        $include_product = true;
    } elseif ($positive == 2) {
        // Include products with stock >= 0
        if ($stock >= 0) {
            $include_product = true;
        }
    } elseif ($positive == 0) {
        // Include products with stock < 0
        if ($stock < 0) {
            $include_product = true;
        }
    }

    if ($include_product) {
        // Prepare the action buttons
        $actionBtn = '<div class="dropdown"><a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown">
                        <i class="flaticon-more-1"></i></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="kt-nav">
                                <li class="kt-nav__item">
                                    <a data-toggle="modal" data-target="#kt_modal_add_purchase_bag" class="kt-nav__link" onclick="PurchaseBagLoad(\'' . $name . '\')"><i class="kt-nav__link-icon flaticon2-send-1"></i><span class="kt-nav__link-text">Add to bag</span></a>
                                </li>
                                <li class="kt-nav__item">
                                    <a class="kt-nav__link" onclick="updated_stock_toggle(\'' . $row_id . '\')"><i class="kt-nav__link-icon flaticon2-graph-1"></i><span class="kt-nav__link-text">Updated Stock Toggle</span></a>
                                </li>
                                <li class="kt-nav__item">
                                    <a class="kt-nav__link" onclick="archive_product(\'' . $row_id . '\')"><i class="kt-nav__link-icon flaticon2-cross"></i><span class="kt-nav__link-text">Archive</span></a>
                                </li>
                            </ul>
                        </div>
                    </div>';

        $url = '<strong><a href="?page=product_details&pr=' . urlencode($name) . '" target="_blank">' . strtoupper($name) . '</a></strong>';

        $updated_stock_date = $row['updated_stock_date'] ? date('d-m-Y', strtotime($row['updated_stock_date'])) : '---';
        $updated_price_date = $row['updated_price_date'] ? date('d-m-Y', strtotime($row['updated_price_date'])) : '---';

        $aoutput[] = [
            'SN' => $count++,
            'Name' => $url,
            'Description' => $row['description'],
            'Alias' => $row['aliases'],
            'Updated_Stock' => $row['updated_stock'],
            'Updated_Stock_Date' => $updated_stock_date,
            'Updated_Price' => $row['updated_price'],
            'Updated_Price_Date' => $updated_price_date,
            'Group' => strtoupper($row['group']),
            'Category' => strtoupper($row['category']),
            'Sub-Category' => strtoupper($row['sub_category']),
            'Unit' => strtoupper($row['unit']),
            'Cost' => number_format($row['cost'], 2),
            'Rate' => number_format($row['rate'], 2),
            'Tax' => $row['tax'],
            'HSN' => $row['hsn'],
            'Unit' => $stock,
            'Actions' => $actionBtn
        ];
    }
}

// Now, after filtering and calculating stocks, we have $aoutput containing the products to display

// Calculate total number of filtered products
$total_filtered = count($aoutput);

// Calculate pagination details
$pages = ($perpage > 0) ? ceil($total_filtered / $perpage) : 0;

// Apply pagination to the dataset
$paginated_data = array_slice($aoutput, $start, $perpage);

// Prepare final output with pagination
$output = [
    'meta' => [
        "page" => $page,
        "pages" => $pages,
        "perpage" => $perpage,
        "total" => $total_filtered,
        "sort" => 'asc',
        "field" => 'SN'
    ],
    'data' => $paginated_data
];

// Output as JSON
echo json_encode($output);
?>
