
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
<style>
    .header{
        position:sticky;
        top: 0 ;
        background: white;
    }
</style>

<table class="table table-bordered table-striped table-hover " >
	<thead>
	    <tr>
	      <th scope="col" class="header">SN</th>
	      <th scope="col" class="header">PI No</th>
	      <th scope="col" class="header">PO No</th>
	      <th scope="col" class="header">PO Date</th>
	      <th scope="col" class="header">Product Name</th>
	      <th scope="col" class="header">Quantity</th>
	      <th scope="col" class="header">Received</th>
	      <th scope="col" class="header">Current Stock</th>
	    </tr>
	</thead>
	<tbody>

<?php
include ("connect.php");

session_start();

$count = 1;

$sql = "SELECT * FROM purchase_order WHERE status = '0'";
$query = $db->query($sql);
while($row = $query->fetch_assoc()){

    $po_items = json_decode($row['items'], true);
    $po_len = sizeof($so_items['product']);

    $po_no = $row['po_no'];

    $sql_check = "SELECT COUNT(*) AS total FROM purchase_invoice WHERE po_no LIKE '%$po_no%'";
    $query_check = $db->query($sql_check);
    $row_check = $query_check->fetch_assoc();

    if($row_check['total'] > 0){

    	$sql_check = "SELECT * FROM purchase_invoice WHERE po_no LIKE '%$po_no%'";
	    $query_check = $db->query($sql_check);
	    $row_check = $query_check->fetch_assoc();

    	for($ii=0;$ii<$po_len;$ii++){

    		$name = $po_items['product'][$ii];

	    	$sql_pr = "SELECT * FROM product WHERE name = '$name'";
			$query_pr = $db->query($sql_pr);
			$row_pr = $query_pr->fetch_assoc();

	    	$stock = $row_pr['opening_stock'];

		    // Sales
		    $sql_tmp = "SELECT * FROM purchase_invoice WHERE items LIKE '%$name%'";
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
		    $sql_tmp = "SELECT * FROM sales_order WHERE items LIKE '%$name%' AND collected = '1' AND `status` = '0'";
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
		    $sql_tmp = "SELECT * FROM purchase_invoice WHERE items LIKE '%$name%'";
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

		    $pr_search="\"".$name."\"";

		    // Assemblies
		    $sql_tmp = "SELECT * FROM assembly_operation WHERE composite = '$name' AND `operation` = 'Assembled'";
		    $query_tmp = $db->query($sql_tmp);
		    while($row_tmp = $query_tmp->fetch_assoc()){
		        $stock += $row_tmp['quantity'];
		    }

		    $sql_tmp = "SELECT * FROM assembly_operation WHERE items LIKE '%$pr_search%' AND `operation` = 'Assembled'";
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
		    $sql_tmp = "SELECT * FROM assembly_operation WHERE composite = '$name' AND `operation` = 'Disassembled'";
		    $query_tmp = $db->query($sql_tmp);
		    while($row_tmp = $query_tmp->fetch_assoc()){
		        $stock -= $row_tmp['quantity'];
		    }

		    $sql_tmp = "SELECT * FROM assembly_operation WHERE items LIKE '%$pr_search%' AND `operation` = 'Disassembled'";
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

	    	?>
	    	<tr>
		      <th scope="row"><?php echo $count++; ?></th>
		      <td><?php echo $row_check['pi_no']; ?></td>
		      <td><?php echo $row['po_no']; ?></td>
		      <td><?php echo $row['po_date']; ?></td>
		      <td><?php echo $so_items['product'][$ii]; ?></td>
		      <td style="text-align: center;"><?php echo $so_items['quantity'][$ii]; ?></td>
		      <td style="text-align: center;"><?php echo $so_items['received'][$ii]; ?></td>
		      <td style="text-align: center;"><strong><?php echo $stock; ?></strong></td>
		    </tr>
    <?php }
	}
}

?>

</tbody>
</table>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>