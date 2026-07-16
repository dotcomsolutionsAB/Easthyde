<?php
	date_default_timezone_set('Asia/Kolkata');
	$db = new mysqli('localhost','u407193404_easthyde','7Fu9IpkU:','u407193404_easthyde');
	if($db->connect_errno){
		die('Sorry, We are having some errors');
	}

    if (version_compare(phpversion(), '7.1', '>=')) {
        ini_set( 'serialize_precision', -1 );
    }

	
?>