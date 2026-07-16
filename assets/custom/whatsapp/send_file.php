<?php

$data = array(
    'phone_no' => '+918961043773', 
    'key' => '1d28cb610052ad20adaccd2a4960978fdf00060b4f0e143b', 
    'url' => 'http://crm.ammarindustrial.in/assets/pdf/quotations/Quotation_AICQ-0076_31072020.pdf'
);
$data_string = json_encode($data);
$ch = curl_init('http://send.woonotif.com/api/send_file_url');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($data_string)
    )
);
$result = curl_exec($ch);

echo json_encode($result);

?>