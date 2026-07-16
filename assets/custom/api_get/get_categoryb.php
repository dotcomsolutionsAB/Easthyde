<?php

session_start();
require_once "../connect.php";

// Ensure the term is set and sanitized to avoid SQL injection
$term = isset($_REQUEST["term"]) ? trim($_REQUEST["term"]) : '';

$json = array("results" => array());


    // Sanitize the input to avoid SQL injection
    $term = $db->real_escape_string($term);
    
    // SQL query with LIKE clause to search for categories
    $sql = "SELECT DISTINCT(category) FROM expense WHERE `category` LIKE '%$term%' ORDER BY `category`";
    $query = $db->query($sql);

    // Fetch results and prepare JSON response
    while ($row = $query->fetch_assoc()) {
        $json["results"][] = ['id' => $row['category'], 'text' => strtoupper($row['category'])];
    }

// Send JSON response
echo json_encode($json);

?>
