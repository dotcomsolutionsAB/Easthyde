<?php

	// Propagates a product rename to the log table and every document type that
	// stores product names inline (assembly/quotation/sales/purchase docs).
	function product_rename_cascade($db, $orig_name, $name, $log_user, $log_date) {

		$sql = "INSERT INTO `product_logs`(`old_name`, `new_name`, `log_user`, `log_date`) VALUES ('$orig_name','$name','$log_user','$log_date')";
		$db->query($sql);

		$sql = "UPDATE assembly SET `composite` = '$name' WHERE `composite` LIKE '$orig_name'";
		$db->query($sql);

		$sql = "UPDATE assembly_operation SET `composite` = '$name' WHERE `composite` LIKE '$orig_name'";
		$db->query($sql);

		$product_name = '\"'.$name.'\"';
		$orig_name_q = '\"'.$orig_name.'\"';

		$sql = "UPDATE quotation SET `items` = REPLACE(`items`, '$orig_name_q', '$product_name') WHERE `items` LIKE '%$orig_name_q%'";
		$db->query($sql);
		$sql = "UPDATE sales_order SET `items` = REPLACE(`items`, '$orig_name_q', '$product_name') WHERE `items` LIKE '%$orig_name_q%'";
		$db->query($sql);
		$sql = "UPDATE purchase_order SET `items` = REPLACE(`items`, '$orig_name_q', '$product_name') WHERE `items` LIKE '%$orig_name_q%'";
		$db->query($sql);
		$sql = "UPDATE sales_invoice SET `items` = REPLACE(`items`, '$orig_name_q', '$product_name') WHERE `items` LIKE '%$orig_name_q%'";
		$db->query($sql);
		$sql = "UPDATE purchase_invoice SET `items` = REPLACE(`items`, '$orig_name_q', '$product_name') WHERE `items` LIKE '%$orig_name_q%'";
		$db->query($sql);
		$sql = "UPDATE assembly SET `spares` = REPLACE(`items`, '$orig_name_q', '$product_name') WHERE `spares` LIKE '%$orig_name_q%'";
		$db->query($sql);
		$sql = "UPDATE assembly_operation SET `items` = REPLACE(`items`, '$orig_name_q', '$product_name') WHERE `items` LIKE '%$orig_name_q%'";
		$db->query($sql);
	}

?>
