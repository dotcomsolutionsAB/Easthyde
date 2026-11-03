<?php
if (!function_exists('ensure_payments_remarks_column')) {
	function ensure_payments_remarks_column($db) {
		$query = $db->query("SHOW COLUMNS FROM `payments` LIKE 'remarks'");
		if ($query && $query->num_rows > 0) {
			return true;
		}
		return (bool)$db->query("ALTER TABLE `payments` ADD COLUMN `remarks` TEXT NULL");
	}
}
