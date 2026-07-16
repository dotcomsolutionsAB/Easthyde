<?php

date_default_timezone_set("Asia/India");
require_once "../connect.php";

$validator = array('success' => true, 'messages' => array());
include("token.php");

$mobile = $_REQUEST['whatsapp_number'];
$wa_msg = $_REQUEST['whatsapp_message'];

$numbers = explode(',',$mobile);
$length = sizeof($numbers);
for($i=0;$i<$length;$i++){
    $mob_no = $numbers[$i];

    if(strlen($mob_no) == '10'){
        $mob_no = '91'.$mob_no;
    }
    // Whatsapp
    $post_url = $woonotif_url;
    $post_url .= 'number='.$mob_no;
    $post_url .= '&message='.urlencode($wa_msg);
    $post_url .= '&type=text';
    $post_url .= '&instance_id='.$instance_id;
    $post_url .= '&access_token='.$token;    

    $ch_image = curl_init();
    curl_setopt_array($ch_image, array(
      CURLOPT_URL => $post_url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_SSL_VERIFYHOST => false,
      CURLOPT_SSL_VERIFYPEER => false,
    ));
    $result_image = curl_exec($ch_image);
    // $status = json_encode($result);
    // $timestamp = date('Y-m-d H:i:s',time());

    // $sql_temp = "INSERT INTO whatsapp_logs (`mobile`,`message`,`timestamp`,`status`) VALUES ('$mob_no', '$wa_msg', '$timestamp', '$status')";
    // $query_temp = $db->query($sql_temp);
}

echo json_encode($validator);

?>