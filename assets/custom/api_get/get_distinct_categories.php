<?php
// Database connection
//ini_set("display_errors",1);
session_start();
require_once "../connect.php";

$query = "SELECT DISTINCT category FROM expense"; // Adjust table name as necessary
$result = $db->query($query);

$categories = [];
while ($row = $result->fetch_assoc()) {
    $categories[] = $row['category'];
}

echo json_encode($categories);
?>