<?php

session_start();
require_once "../connect.php";

$term = $_REQUEST["term"] ?? '';
$term = (string)($term ?? '');
$term = str_replace(" ", "", $term);
$term = str_replace(".", "", $term);

$sql = "
SELECT 'supplier' AS type, id, name
FROM suppliers 
WHERE REPLACE(REPLACE(`name`, ' ', ''), '.', '') LIKE '%$term%' 

UNION ALL 

SELECT 'client' AS type, id, name
FROM clients 
WHERE REPLACE(REPLACE(`name`, ' ', ''), '.', '') LIKE '%$term%' 

ORDER BY type, name";

$query = $db->query($sql);

$json = array("results" => array());

if ($query) {
while ($row = $query->fetch_assoc()) {
    // Adjust the 'id' field and 'text' based on the type
    $json["results"][] = [
        'id' => $row['name'], // You may want a different unique ID if applicable
        'text' => $row['name'],
        'type' => $row['type'] // Include the type for clarity
    ];
}
}

echo json_encode($json);
?>
