<?php
session_start();
require_once "../connect.php";

$output = true;

$product_name = trim(strtoupper($_REQUEST['product_name'] ?? ''));

$sql = "SELECT name FROM product";
$query = $db->query($sql);

if ($query) {
while($row = $query->fetch_assoc()){

    if($row['name'] === $product_name)
        $output = false;
}
}

echo json_encode($output);

?>