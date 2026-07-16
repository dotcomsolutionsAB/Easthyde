<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
<style>
    .header{
        position:sticky;
        top: 0 ;
        background: white;
    }
</style>

<title>M. M. Lucky Enterprise Stock Mapping</title>

<table class="table table-bordered table-striped table-hover " >
	<thead>
	    <tr>
	      <th scope="col" class="header">SN</th>
	      <th scope="col" class="header">Product</th>
	      <th scope="col" class="header">21-22 Closing Stock</th>
	      <th scope="col" class="header">22-23 Opening Stock</th>
	    </tr>
	</thead>
	<tbody>

<?php
include ("connect.php");
session_start();

$start  = '2021-04-01';
$end    = '2022-03-31';

$count = 1;
$sql_pr = "SELECT * FROM product";
$query_pr = $db->query($sql_pr);
while($row_pr = $query_pr->fetch_assoc())
{
    $id = $row_pr['id'];

    $group = $row_pr['group'];
    $group = str_replace(" ","_",$group);

    $opening_stock = json_decode($row_pr['new_opening_stock'], true);

    $stock = $opening_stock['stock'][1];
    $name = $row_pr['name'];

    // Sales
    $sql_tmp = "SELECT * FROM sales_invoice WHERE items LIKE '%$name%' AND `si_date` BETWEEN '$start' AND '$end'";
    $query_tmp = $db->query($sql_tmp);
    while($row_tmp = $query_tmp->fetch_assoc()){
        $items = json_decode($row_tmp['items'], true);
        $len = sizeof($items['product']);
        for($i=0;$i<$len;$i++){
            if($items['product'][$i] == $name)
            {
                if($row_tmp['series'] == 'SECONDARY' ){
                    $stock -= $items['effective_quantity'][$i];
                }else{
                    $stock -= $items['quantity'][$i];
                }
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

    if($stock != $opening_stock['stock'][2])
    {

        $opening_stock['stock'][2] = $stock;

        // $opening_stock = json_encode($opening_stock);
        // $sql_add = "UPDATE product SET `new_opening_stock` = '$opening_stock' WHERE `id` = '$id'";
        // $query_add = $db->query($sql_add);
	?>
	<tr>
      <td style="text-align:center;"><?php echo $count++; ?></td>
      <td style="text-align:left;"><?php echo $name; ?></td>
      <td style="text-align:center;"><?php echo $stock; ?></td>
      <td style="text-align:center;"><?php echo $opening_stock['stock'][2]; ?></td>
    </tr>

<?php 
}}
?>



