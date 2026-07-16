<?php
	date_default_timezone_set('Asia/Kolkata');

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
			if ($key !== '' && getenv($key) === false) {
				putenv("$key=$value");
				$_ENV[$key] = $value;
			}
		}
	}

	$dbHost = getenv('DB_HOST') ?: 'localhost';
	$dbUser = getenv('DB_USER') ?: '';
	$dbPass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';
	$dbName = getenv('DB_NAME') ?: '';

	$db = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
	if ($db->connect_errno) {
		die('Sorry, We are having some errors');
	}

	if (version_compare(phpversion(), '7.1', '>=')) {
		ini_set('serialize_precision', -1);
	}
?>
