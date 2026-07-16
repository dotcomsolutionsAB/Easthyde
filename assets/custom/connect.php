<?php
	date_default_timezone_set('Asia/Kolkata');

	// PHP 8.1+ defaults to throwing mysqli_sql_exception — restore soft-fail behavior
	mysqli_report(MYSQLI_REPORT_OFF);

	$envFile = dirname(__DIR__, 2) . '/.env';
	if (is_readable($envFile)) {
		foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
			$line = trim($line);
			if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) {
				continue;
			}
			list($key, $value) = explode('=', $line, 2);
			$key = trim($key);
			$value = trim($value, " \t\"'");
			if ($key === '') {
				continue;
			}
			$_ENV[$key] = $value;
			$_SERVER[$key] = $value;
			if (function_exists('putenv')) {
				@putenv($key . '=' . $value);
			}
		}
	}

	$dbHost = $_ENV['DB_HOST'] ?? 'localhost';
	$dbUser = $_ENV['DB_USER'] ?? '';
	$dbPass = $_ENV['DB_PASS'] ?? '';
	$dbName = $_ENV['DB_NAME'] ?? '';

	$db = @new mysqli($dbHost, $dbUser, $dbPass, $dbName);
	if ($db->connect_errno) {
		die('Sorry, We are having some errors');
	}

	if (version_compare(phpversion(), '7.1', '>=')) {
		ini_set('serialize_precision', -1);
	}
?>
