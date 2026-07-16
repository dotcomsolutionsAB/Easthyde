<?php

require_once "../connect.php";

$name = $_REQUEST['member_id'];

$sql = "SELECT * FROM product WHERE name = '$name'";
$query = $db->query($sql);
$row = $query->fetch_assoc();


$name=$row['name'];

$sql_year = "SELECT * FROM year WHERE current = '1'";
$query_year = $db->query($sql_year);
$row_year = $query_year->fetch_assoc();

$year = $row_year['year'];
$start = $row_year['start'];
$end = $row_year['end'];

$new_opening_stock = json_decode($row['new_opening_stock'],true);
$len = sizeof($new_opening_stock['year']);
// echo $new_opening_stock['year'][1];

for($i=0;$i<$len;$i++)
{
    if($new_opening_stock['year'][$i] == $year)
    {
        $opening_stock = $new_opening_stock['stock'][$i];
    }
}
// $opening_stock = $row_pr['opening_stock'];
$stock = $opening_stock;

// Sales
$sql_tmp = "SELECT * FROM sales_invoice WHERE items LIKE '%$name%' AND `si_date` BETWEEN '$start' AND '$end'";
$query_tmp = $db->query($sql_tmp);
while($row_tmp = $query_tmp->fetch_assoc()){
    $items = json_decode($row_tmp['items'], true);
    $len = sizeof($items['product']);
    for($i=0;$i<$len;$i++){
        if($items['product'][$i] == $name)
        {
                $stock -= $items['quantity'][$i];
            }
        }
    
}

// Sales
$sql_tmp = "SELECT * FROM sales_order WHERE items LIKE '%$name%' AND collected = '1' AND `status` = '0' AND `so_date` BETWEEN '$start' AND '$end'";
$query_tmp = $db->query($sql_tmp);
while($row_tmp = $query_tmp->fetch_assoc()){
    $items = json_decode($row_tmp['items'], true);
    $len = sizeof($items['product']);
    for($i=0;$i<$len;$i++){
        if($items['product'][$i] == $name)
        {
            $stock -= $items['quantity'][$i];
        }
    }
}

// Purchase
$sql_tmp = "SELECT * FROM purchase_invoice WHERE items LIKE '%$name%' AND `pi_date` BETWEEN '$start' AND '$end'";
$query_tmp = $db->query($sql_tmp);
while($row_tmp = $query_tmp->fetch_assoc()){
    $items = json_decode($row_tmp['items'], true);
    $len = sizeof($items['product']);
    for($i=0;$i<$len;$i++){
        if($items['product'][$i] == $name)
        {
            $stock += $items['quantity'][$i];
        }
    }
}

$sql_tmp = "SELECT * FROM credit_note WHERE items LIKE '%$name%' AND `cn_date` BETWEEN '$start' AND '$end'";
    $query_tmp = $db->query($sql_tmp);
    while($row_tmp = $query_tmp->fetch_assoc()){
        $items = json_decode($row_tmp['items'], true);
        $len = sizeof($items['product']);
        for($i=0;$i<$len;$i++){
            if($items['product'][$i] == $name)
            {
                $stock += $items['quantity'][$i];
            }
        }
    }

$sql_tmp = "SELECT * FROM debit_note WHERE items LIKE '%$name%' AND `dn_date` BETWEEN '$start' AND '$end'";
$query_tmp = $db->query($sql_tmp);
while($row_tmp = $query_tmp->fetch_assoc()){
    $items = json_decode($row_tmp['items'], true);
    $len = sizeof($items['product']);
    for($i=0;$i<$len;$i++){
        if($items['product'][$i] == $name)
        {
            $stock -= $items['quantity'][$i];
        }
    }
}

$pr_search="\"".$name."\"";

// Assemblies
$sql_tmp = "SELECT * FROM assembly_operation WHERE composite = '$name' AND `operation` = 'Assembled' AND `log_date` BETWEEN '$start' AND '$end'";
$query_tmp = $db->query($sql_tmp);
while($row_tmp = $query_tmp->fetch_assoc()){
    $stock += $row_tmp['quantity'];
}

$sql_tmp = "SELECT * FROM assembly_operation WHERE items LIKE '%$pr_search%' AND `operation` = 'Assembled' AND `log_date` BETWEEN '$start' AND '$end'";
$query_tmp = $db->query($sql_tmp);
while($row_tmp = $query_tmp->fetch_assoc()){
    $items = json_decode($row_tmp['items'], true);
    $len = sizeof($items['product']);
    for($i=0;$i<$len;$i++){
        if($items['product'][$i] == $name)
        {
            $qty = $row_tmp['quantity'] * $items['quantity'][$i];
            $stock -= $qty;
        }
    }
}

// Disassemble
$sql_tmp = "SELECT * FROM assembly_operation WHERE composite = '$name' AND `operation` = 'Disassembled' AND `log_date` BETWEEN '$start' AND '$end'";
$query_tmp = $db->query($sql_tmp);
while($row_tmp = $query_tmp->fetch_assoc()){
    $stock -= $row_tmp['quantity'];
}

$sql_tmp = "SELECT * FROM assembly_operation WHERE items LIKE '%$pr_search%' AND `operation` = 'Disassembled' AND `log_date` BETWEEN '$start' AND '$end'";
$query_tmp = $db->query($sql_tmp);
while($row_tmp = $query_tmp->fetch_assoc()){
    $items = json_decode($row_tmp['items'], true);
    $len = sizeof($items['product']);
    for($i=0;$i<$len;$i++){
        if($items['product'][$i] == $name)
        {
            $qty = $row_tmp['quantity'] * $items['quantity'][$i];
            $stock += $qty;
        }
    }
}

$db->close();
 
echo $stock;

?>