<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
<style>
    .header{
        position:sticky;
        top: 0 ;
        background: white;
    }
</style>

<title>Ammar Sales Matching</title>

<table class="table table-bordered table-striped table-hover " >
	<thead>
	    <tr>
	      <th scope="col" class="header">SN</th>
	      <th scope="col" class="header">SI No</th>
	      <th scope="col" class="header">Client</th>
	      <th scope="col" class="header">Calculated Total</th>
	      <th scope="col" class="header">Stored Total</th>
	    </tr>
	</thead>
	<tbody>

<?php
include ("connect.php");
session_start();

$count = 1;
$sql = "SELECT * FROM sales_invoice WHERE si_date BETWEEN '2020-12-01' AND '2020-12-31' AND series != 'SECONDARY'";
$query = $db->query($sql);
while($row = $query->fetch_assoc()){ 

		$total = 0;
		$cgst = 0;
		$sgst = 0;
		$igst = 0;

		$items = json_decode($row['items'], true);
		$l = sizeof($items['product']);

		for($i=0;$i<$l;$i++){

			$line_total = $items['quantity'][$i] * $items['price'][$i] - ($items['quantity'][$i] * $items['price'][$i] * $items['discount'][$i] / 100);
			$total += $line_total;

			$cgst += $items['cgst'][$i];
			$sgst += $items['sgst'][$i];
			$igst += $items['igst'][$i];

		}

		// $total = number_format($total,2);

		$addons_array = json_decode($row['addons'], true);
		$tax_array = json_decode($row['tax'], true);

		$cgst += $addons_array['pf']['cgst'] + $addons_array['freight']['cgst'];
		$sgst += $addons_array['pf']['sgst'] + $addons_array['freight']['sgst'];
		$igst += $addons_array['pf']['igst'] + $addons_array['freight']['igst'];

		$total += $addons_array['pf']['value'] + $addons_array['freight']['value'] + $cgst + $sgst + $igst + $addons_array['roundoff'];

	?>

	    <tr>
	      <td style="text-align:center;"><?php echo $count++; ?></td>
	      <td><?php echo $row['si_no']; ?></td>
	      <td style="text-align:center;"><?php echo $row['client_name']; ?></td>
	      <td style="text-align:center;"><?php echo $total; ?></td>
	      <th style="text-align:center;"><?php echo $row['total']; ?></th>
	    </tr>

<?php } ?>
	</tbody>
</table>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>