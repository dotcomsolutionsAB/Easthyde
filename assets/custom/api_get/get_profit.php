<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);
session_start();
require_once "../connect.php";

// Fetch necessary parameters from the request
$draw = intval($_REQUEST['draw'] ?? 1);
$start_date = date('Y-m-d', strtotime($_REQUEST['start_date'] ?? 'now'));
$end_date = date('Y-m-d', strtotime($_REQUEST['end_date'] ?? 'now'));
$series = $_REQUEST['series'] ?? '';
$search_value = $_REQUEST['search']['value'] ?? ''; 
$start = intval($_REQUEST['start'] ?? 0);
$length = intval($_REQUEST['length'] ?? 10);

$profit_entries = array();

try {
    // Sanitize inputs
    $series = $db->real_escape_string($series);
    $start_date = $db->real_escape_string($start_date);
    $end_date = $db->real_escape_string($end_date);
    $search_value = $db->real_escape_string($search_value);

    // Build the base SQL query
    $sql = "SELECT * FROM sales_invoice 
            WHERE series = '$series' AND si_date BETWEEN '$start_date' AND '$end_date'";
    
    // Add search filtering if search_value is provided
    if (!empty($search_value)) {
        $sql .= " AND (client_name LIKE '%$search_value%' OR si_no LIKE '%$search_value%')";

    }

    $sql .= " ORDER BY si_date DESC";
    
    // Add pagination only if $length is not -1
    if ($length != -1) {
        $sql .= " LIMIT $start, $length";
    }

    // Execute the query
    $result = $db->query($sql);
    if ($result === false) {
        throw new Exception("Failed to execute query: " . $db->error);
    }

    // Fetch filtered entry count
    $total_filtered_entries_query = "SELECT COUNT(*) as count FROM sales_invoice 
                                     WHERE series = '$series' AND si_date BETWEEN '$start_date' AND '$end_date'";
    $total_filtered_entries_result = $db->query($total_filtered_entries_query);
    if ($total_filtered_entries_result === false) {
        throw new Exception("Failed to execute count query: " . $db->error);
    }

    $count_row = ($total_filtered_entries_result) ? $total_filtered_entries_result->fetch_assoc() : null;
    $total_filtered_entries = $count_row['count'] ?? 0;

    // Loop through the result and build the response array
    if ($result) {
    while ($invoice = $result->fetch_assoc()) {
        $items = json_decode($invoice['items'] ?? '', true);
        if (!is_array($items)) {
            $items = [];
        }
        if (!is_array($items) || !isset($items['product'], $items['quantity'], $items['price'])) {
            continue;
        }

        $products = $items['product'];
        $quantities = $items['quantity'];
        $prices = $items['price'];
        $discount=$items['discount'];
       // echo ("price ".$prices." Discount ".$discount);

        for ($i = 0; $i < count($products); $i++) {
            $product_name = $products[$i];
            $quantity = $quantities[$i];
            $price = $prices[$i];
            $discounts = isset($discount[$i]) && is_numeric($discount[$i]) ? $discount[$i] : 0;
           // echo ("price ".$prices[$i]." Discount ".$discounts);
            $dis=($discounts!=0)?($discounts/100)*$price:0;
            //echo ("price ".$prices[$i]." Discount ".$dis);

            // Fetch the cost price for the product
           

                       // Initialize cost to 0
                       $cost = 0;

                       $purchase_query = "SELECT items FROM purchase_invoice ORDER BY pi_date DESC";
                       $purchase_result = $db->query($purchase_query);
                       
                       $cost = 0;
                       if ($purchase_result !== false && $purchase_result->num_rows > 0) {
                           if ($purchase_result) {
                           while ($purchase_data = $purchase_result->fetch_assoc()) {
                               $purchase_items = json_decode($purchase_data['items'] ?? '', true);
                               if (!is_array($purchase_items)) {
                                   $purchase_items = [];
                               }
                       
                               if (is_array($purchase_items) && isset($purchase_items['product'], $purchase_items['price'])) {
                                   $purchase_products = $purchase_items['product'];
                                   $purchase_prices = $purchase_items['price'];
                       
                                   // Find the matching product's price
                                   $index = array_search($product_name, $purchase_products);
                                   if ($index !== false && isset($purchase_prices[$index])) {
                                       $cost = (float)$purchase_prices[$index];
                                       break; // Stop once the cost is found
                                   }
                               }
                           }
                           }
                       }
                       
           
                       // If not found in purchase_invoice, check the product table
                       if ($cost == 0) {
                           $product_query = "SELECT cost FROM product WHERE name = '$product_name'";
                           $product_result = $db->query($product_query);
           
                           if ($product_result !== false && $product_result->num_rows > 0) {
                               $product_data = ($product_result) ? $product_result->fetch_assoc() : null;
                               $cost = isset($product_data['cost']) && is_numeric($product_data['cost']) ? $product_data['cost'] : 0;
                           }
                       }
           
            // Calculate total cost, total sales, and profit
            $total_cost = $cost * $quantity;
            $total_sales = ($price-$dis) * $quantity;
            $profit = $total_sales - $total_cost;
            $notes = ($invoice['notes']!='') ? $invoice['notes'] : 'Nil';
            // Ensure we are passing floats to number_format
            $profit_entries[] = [
                'date' => date('d-m-Y', strtotime($invoice['si_date'])),
                'product' => $product_name,
                'quantity' => intval($quantity), // Ensure quantity is integer
                'invoice_no' => $invoice['si_no'],
                'notes'=> $notes,
                'sale_price' => number_format((float)$price-$dis, 2),  // Ensure price is float
                'cost_price' => number_format((float)$cost, 2) , // Ensure cost is float or null
                'profit' => number_format((float)$profit, 2) // Ensure profit is float
            ];
        }
    }
    }

    // Send the response to DataTables
    $response = [
        'draw' => intval($draw),
        'recordsTotal' => $total_filtered_entries,
        'recordsFiltered' => $total_filtered_entries,
        'data' => $profit_entries
    ];

    echo json_encode($response);

} catch (Exception $e) {
    // Return error response in case of exception
    echo json_encode(['error' => $e->getMessage()]);
}
