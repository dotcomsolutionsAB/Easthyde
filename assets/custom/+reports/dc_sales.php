<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
<style>
    .header{
        position:sticky;
        top: 0 ;
        background: white;
    }
</style>

<title>Sales - Ammar Industrial</title>

<table class="table table-bordered table-striped table-hover " >
	<thead>
	    <tr>
	      <th scope="col" class="header">SN</th>
	      <th scope="col" class="header">Invoice</th>
	      <th scope="col" class="header">Date</th>
	      <th scope="col" class="header">Items CGST</th>
	      <th scope="col" class="header">Items SGST</th>
	      <th scope="col" class="header">Items IGST</th>
	      <th scope="col" class="header">CGST</th>
	      <th scope="col" class="header">SGST</th>
	      <th scope="col" class="header">IGST</th>
	      <th scope="col" class="header">Calculated Total</th>
	      <th scope="col" class="header">Total</th>
	    </tr>
	</thead>
	<tbody>

<?php
include ("../connect.php");
session_start();

$count = 1;
$sql = "SELECT * FROM sales_invoice WHERE si_date BETWEEN '2022-07-01' AND '2022-07-31' ORDER BY si_no";
$query = $db->query($sql);
while($row = $query->fetch_assoc()){ 

	$tax = json_decode($row['tax'], true);
	$addons = json_decode($row['addons'], true);
	$items = json_decode($row['items'], true);
	$len = sizeof($items['product']);

	$cgst=0;
	$sgst=0;
	$igst=0;

	$total = 0;

	for($i=0;$i<$len;$i++){
		$cgst += $items['cgst'][$i];
		$sgst += $items['sgst'][$i];
		$igst += $items['igst'][$i];

		$line_total = (float)($items['quantity'][$i] ?? 0)*(float)($items['price'][$i] ?? 0)*(100-(float)($items['discount'][$i] ?? 0))/100;
		$total+=$line_total;
	}

	$cgst += $addons['freight']['cgst'] + $addons['pf']['cgst'];
	$sgst += $addons['freight']['sgst'] + $addons['pf']['sgst'];
	$igst += $addons['freight']['igst'] + $addons['pf']['igst'];

	$total += $addons['freight']['value'] + $addons['pf']['value'] + $cgst + $sgst + $igst + $addons['roundoff'];

	if($total != $row['total']){

	?>

	    <tr>
	      <td style="text-align:center;"><?php echo $count++; ?></td>
	      <td><?php echo $row['si_no']; ?></td>
	      <td style="text-align:center;"><?php echo $row['si_date']; ?></td>
	      <td style="text-align:center;"><?php echo $cgst; ?></td>
	      <td style="text-align:center;"><?php echo $sgst; ?></td>
	      <td style="text-align:center;"><?php echo $igst; ?></td>
	      <td style="text-align:center;"><?php echo $tax['cgst']; ?></td>
	      <td style="text-align:center;"><?php echo $tax['sgst']; ?></td>
	      <td style="text-align:center;"><?php echo $tax['igst']; ?></td>
	      <th style="text-align:center;"><?php echo $total; ?></th>
	      <th style="text-align:center;"><?php echo $row['total']; ?></th>
	    </tr>

<?php } }?>
	</tbody>
</table>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>