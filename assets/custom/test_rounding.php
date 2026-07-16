<?php

date_default_timezone_set('Asia/Kolkata');

if (version_compare(phpversion(), '7.1', '>=')) {
    ini_set( 'serialize_precision', -1 );
}

$number = $_REQUEST['number'];

echo "round() : ".round($number).'<br/>';
echo "round( ,2) : ".round($number,2).'<br/>';
echo "number_format() : ".number_format($number,2, '.', '').'<br/>';

echo $number;

?>