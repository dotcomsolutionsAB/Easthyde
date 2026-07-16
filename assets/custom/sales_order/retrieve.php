<?php
session_start();
require_once "../connect.php";

// Set locale for currency formatting
setlocale(LC_MONETARY, 'en_IN');

// Retrieve session variables for date range
$dt_start = $_SESSION['start'] ?? '';
$dt_end = $_SESSION['end'] ?? '';

// Fetch pagination and query details from the request
$pagination = $_REQUEST['pagination'] ?? [];
$query_array = $_REQUEST['query'] ?? [];
$sort_array = $_REQUEST['sort'] ?? [];

// Sanitize and process the search query
$query = $query_array['generalSearch'] ?? '';
$query = str_replace([' ', '-', '.'], '', $query);

// Retrieve and set default filters for status, user, and product
$status = $query_array['status'] ?? '%';
$user = $query_array['user'] ?? '%';
$product = $query_array['product'] ?? '%';

// Fetch the latest sales order
$sql_fetch = "SELECT * FROM sales_order ORDER BY id DESC LIMIT 1";
$query_fetch = $db->query($sql_fetch);
$row_fetch = $query_fetch->fetch_assoc();

// Query to get the total number of matching records
$sql_1 = "SELECT COUNT(*) AS total 
          FROM sales_order 
          WHERE (REPLACE(REPLACE(`so_no`, ' ', ''), '-', '') LIKE '%$query%' 
          OR REPLACE(REPLACE(REPLACE(`client_name`, '-', ''), ' ', ''), '.', '') LIKE '%$query%' 
          OR `total` LIKE '%$query%' 
          OR `mobile` LIKE '%$query%') 
          AND `status` LIKE '$status' 
          AND `log_user` LIKE '$user' 
          AND `items` LIKE '%$product%' 
          AND `so_date` BETWEEN '$dt_start' AND '$dt_end'";
$query_1 = $db->query($sql_1);
$row_1 = $query_1->fetch_assoc();

// Pagination setup
$perpage = $pagination['perpage'] ?? 10;
$page = $pagination['page'] ?? 1;
$start = ($page - 1) * $perpage;
$pages = ceil($row_1['total'] / $perpage);

// Initialize output array
$output = [
    'meta' => [
        'page' => $page,
        'pages' => $pages,
        'perpage' => $perpage,
        'total' => $row_1['total'],
        'sort' => 'asc',
        'field' => 'SN'
    ],
    'data' => []
];

// Main query to fetch the sales orders
$sql = "SELECT * FROM sales_order 
        WHERE (REPLACE(REPLACE(`so_no`, ' ', ''), '-', '') LIKE '%$query%' 
        OR REPLACE(REPLACE(REPLACE(`client_name`, '-', ''), ' ', ''), '.', '') LIKE '%$query%' 
        OR `total` LIKE '%$query%' 
        OR `mobile` LIKE '%$query%') 
        AND `status` LIKE '$status' 
        AND `log_user` LIKE '$user' 
        AND `items` LIKE '%$product%' 
        AND `so_date` BETWEEN '$dt_start' AND '$dt_end' 
        ORDER BY `id` DESC LIMIT $start, $perpage";
$query = $db->query($sql);

// Loop through the fetched sales orders and prepare the output
$count = 1;
while ($row = $query->fetch_assoc()) {
    
    $username = $_SESSION['username'];
    $userlevel = $_SESSION['userlevel'];

    $sql_access = "SELECT * FROM users WHERE `username` = '$username'";
    $query_access = $db->query($sql_access);
    $row_access = $query_access->fetch_assoc();

    $menu_access = json_decode($row_access['access'], true);
    
    $edit = '';
    $delete = '';

    if($menu_access['sales_order']['edit'] == '1' || $userlevel == "sadmin_df56fdg"){

            $edit = '<li class="kt-nav__item"><a href="javascript:;" onclick="editSalesOrder(\''.$row['id'].'\')" title="Edit Sales Order" class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-contract"></i><span class="kt-nav__link-text">Edit</span></a></li>';
    }
    
    if($menu_access['sales_order']['delete'] == '1' || $userlevel == "sadmin_df56fdg"){

            $delete = '<li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#delete_sales_order" title="Delete" onclick="removeSalesOrder(\''.$row['id'].'\')"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-trash"></i><span class="kt-nav__link-text">Delete</span></a></li>';
    }
     
    
    
    // Set the status options for Completed/Pending
    $option = ($row['status'] == 0)
        ? '<li class="kt-nav__item"><a href="javascript:;" onclick="setStatus(\'' . $row['so_no'] . '\', \'1\', \'sales_order\')" class="kt-nav__link"><i class="kt-nav__link-icon flaticon-like"></i><span class="kt-nav__link-text">Completed</span></a></li>'
        : '<li class="kt-nav__item"><a href="javascript:;" onclick="setStatus(\'' . $row['so_no'] . '\', \'0\', \'sales_order\')" class="kt-nav__link"><i class="kt-nav__link-icon flaticon-like"></i><span class="kt-nav__link-text">Pending</span></a></li>';

    // Prepare action buttons based on user level
    $actionBtn = '<div class="dropdown"><a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown"><i class="flaticon-more-1"></i></a><div class="dropdown-menu dropdown-menu-right"><ul class="kt-nav">';
    // Add specific actions
    $actionBtn .= '<li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#kt_modal_so_note" class="kt-nav__link"><i class="kt-nav__link-icon flaticon-chat"></i><span class="kt-nav__link-text">Add Note</span></a></li>'.$edit.$delete.'
	';
    // Other action buttons here...
    $actionBtn .= $option . '</ul></div></div>';

    // Fetch additional client details
    $client_name = $row['client_name'];
    $sql_temp = "SELECT * FROM clients WHERE name = '$client_name'";
    $query_temp = $db->query($sql_temp);
    $row_temp = $query_temp->fetch_assoc();

    // Prepare output data for each sales order
    $material = ($row['collected'] == 0) ? "Order Received" : "Picked Up";
    $sales_order_link = '<a href="?page=sales_order_notes&so_no=' . $row['so_no'] . '" target="_blank">' . $row['so_no'] . '</a>';
    $client_display = $row['mobile'] != 0 ? $row['client_name'] . "<br>Mob: " . $row['mobile'] : $row['client_name'];

    // Add the sales order data to the output array
    $output['data'][] = [
        'RecordID' => $count++,
        'Number' => $row['so_no'],
        'Name' => $client_display,
        'SalesOrder' => $sales_order_link,
        'Date' => date('d-m-Y', strtotime($row['so_date'])),
        'MaterialStatus' => $material,
        'Status' => $row['status'],
        'User' => $row['log_user'],
        'Amount' => number_format($row['total'], 2),
        'Actions' => $actionBtn
    ];
}

// Output the result as JSON
echo json_encode($output);
?>
