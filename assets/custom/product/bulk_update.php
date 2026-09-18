<?php
	include ("../connect.php");
	include ("../php_replace_improper.php");
	include ("rename_cascade.php");

	session_start();

	$log_user = $_SESSION['username'] ?? '';
	$log_date = date('Y-m-d', strtotime("today"));

	$products = json_decode((string)($_REQUEST['products_json'] ?? '[]'), true);
	if (!is_array($products)) {
		$products = array();
	}

	$updated = 0;
	$failed = array();

	foreach ($products as $item) {

		$id = $item['id'] ?? '';
		$name = replace_improper((string)($item['name'] ?? ''));

		if ($id === '' || $name === '') {
			continue;
		}

		$sql = "SELECT * FROM product WHERE id = '$id'";
		$query = $db->query($sql);
		if (!$query || $query->num_rows === 0) {
			$failed[] = "Product ID $id not found";
			continue;
		}
		$row = $query->fetch_assoc();
		$orig_name = $row['name'] ?? '';

		if ($orig_name === $name) {
			continue;
		}

		$sql = "UPDATE product SET `name`='$name', `log_date`='$log_date' WHERE `id` = '$id'";
		$query = $db->query($sql);

		if ($query !== true) {
			$failed[] = "Failed to update ".$orig_name;
			continue;
		}

		$updated++;

		product_rename_cascade($db, $orig_name, $name, $log_user, $log_date);
	}

	if ($updated === 0 && count($failed) > 0) {
		echo json_encode(array("success"=>false, "messages"=>"No products were updated. ".implode('; ', $failed)));
	} else if (count($failed) > 0) {
		echo json_encode(array("success"=>true, "messages"=>"Updated $updated product(s). Some rows failed: ".implode('; ', $failed)));
	} else if ($updated === 0) {
		echo json_encode(array("success"=>true, "messages"=>"No changes to save"));
	} else {
		echo json_encode(array("success"=>true, "messages"=>"Successfully updated $updated product(s)"));
	}

?>
