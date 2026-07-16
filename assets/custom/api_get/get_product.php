<?php

session_start();
require_once "../connect.php";

$term = $_REQUEST["term"] ?? '';

// Removing spaces and dashes from the search term
$term = (string)($term ?? '');
$term = str_replace(" ", "", $term);
$term = str_replace("-", "", $term);

// Query to search the product table based on the search term
$sql = "SELECT * FROM product 
        WHERE (REPLACE(REPLACE(`name`, ' ', ''), '-', '') LIKE '%$term%' 
            OR REPLACE(REPLACE(`description`, ' ', ''), '-', '') LIKE '%$term%' 
            OR REPLACE(REPLACE(`aliases`, ' ', ''), '-', '') LIKE '%$term%') 
        AND `archive` = 0";
        
$query = $db->query($sql);

$json = array("results" => array());

// Loop through the result set
if ($query) {
while ($row = $query->fetch_assoc()) {
    // Check if the vendor field is not empty
    $vendor = !empty($row['vendor']) ? " - Vendor: " . $row['vendor'] : "";
    
    // Return the product name along with the vendor if available
    $json["results"][] = [
        'id' => $row['name'], 
        'text' => $row['name'] . $vendor
    ];
}
}

// Return the JSON-encoded result
echo json_encode($json);

?>
