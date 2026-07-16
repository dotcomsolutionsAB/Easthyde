<?php
require_once "../connect.php";

$response = array();
$sql = "SELECT year FROM year ORDER BY id DESC";
$query = $db->query($sql);
if ($query) {
    while ($row = $query->fetch_assoc()) {
        $response[] = array(
            "id" => $row['year'],
            "text" => $row['year']
        );
    }
}

echo json_encode($response);
?>
