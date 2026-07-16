<?php
session_start();
require_once "../connect.php";

$output = true;

$client_name = trim(strtoupper($_REQUEST['client_name'] ?? ''));

$sql = "SELECT name FROM clients";
$query = $db->query($sql);

if ($query) {
while($row = $query->fetch_assoc()){

    if($row['name'] === $client_name)
        $output = false;
}
}

$sql = "SELECT name FROM suppliers";
$query = $db->query($sql);

if ($query) {
while($row = $query->fetch_assoc()){

    if($row['name'] === $client_name)
        $output = false;
}
}

echo json_encode($output);

?>