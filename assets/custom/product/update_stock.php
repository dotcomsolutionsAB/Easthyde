<?php
	include ("../connect.php");
    include ("../php_replace_improper.php");
	
	session_start();
	
	// Assuming you have a valid database connection in $db
	
	// Step 1: Fetch all products that have a new_opening_stock column
	$sql = "SELECT id, new_opening_stock FROM product";
	$result = $db->query($sql);
	
	if ($result) {
		// Step 2: Loop through each product
		while ($row = $result->fetch_assoc()) {
			$product_id = $row['id'];
			$new_opening_stock = json_decode($row['new_opening_stock'], true);
	
			// Step 3: Check if the JSON structure contains 'year' and 'stock' keys
			if (isset($new_opening_stock['year']) && isset($new_opening_stock['stock'])) {
				// Step 4: Add the new year '2024-25' and duplicate the stock from '2023-24'
				$new_opening_stock['year'][] = '2024-25';
				$new_opening_stock['stock'][] = end($new_opening_stock['stock']); // Duplicate stock from '2023-24'
	
				// Step 5: Encode the modified JSON back to a string
				$updated_new_opening_stock = json_encode($new_opening_stock);
	
				// Step 6: Update the product with the new JSON data
				$update_sql = "UPDATE product SET news_opening_stock = ? WHERE id = ?";
				$stmt = $db->prepare($update_sql);
				$stmt->bind_param('si', $updated_new_opening_stock, $product_id);
				$stmt->execute();
	
				// Optional: Log or check the result of the update for each product
				if ($stmt->affected_rows > 0) {
					echo "Product ID $product_id updated successfully.<br>";
				} else {
					echo "No changes made for Product ID $product_id.<br>";
				}
	
				$stmt->close();
			} else {
				echo "Invalid JSON structure for Product ID $product_id.<br>";
			}
		}
	} else {
		echo "Failed to fetch products from the database.";
	}
	?>
	