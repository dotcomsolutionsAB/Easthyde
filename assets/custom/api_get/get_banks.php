<?php

session_start();
require_once "../connect.php";

$term = $_REQUEST["term"] ?? '';

$sql = "SELECT * FROM bank WHERE `bank_name` LIKE '%$term%'";
$query = $db->query($sql);

$json = array("results"=>array());

// Fetch results from the database
if ($query) {
while ($row = $query->fetch_assoc()) {
    $json["results"][] = ['id' => $row['bank_name'], 'text' => $row['bank_name']];
}
}

// Add custom entry "CASH (Secondary)"
$json["results"][] = ['id' => 'CASH(Secondary)', 'text' => 'CASH (Secondary)'];

echo json_encode($json);

?>