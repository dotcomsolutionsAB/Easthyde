<?php
if (!function_exists('ensure_receipts_remarks_column')) {
	function ensure_receipts_remarks_column($db) {
		$query = $db->query("SHOW COLUMNS FROM `receipts` LIKE 'remarks'");
		if ($query && $query->num_rows > 0) {
			return true;
		}
		return (bool)$db->query("ALTER TABLE `receipts` ADD COLUMN `remarks` TEXT NULL");
	}
}
