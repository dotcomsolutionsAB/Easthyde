<?php
// ini_set('display_errors', 1);
//Head

session_start();
require_once "../connect.php";
include("token.php");


$pr_no = $_REQUEST['pr_no_whatsapp'];
$mobile = $_REQUEST['pr_whatsapp_number'];

$sql_fetch = "SELECT * FROM proforma WHERE `pr_no` = '$pr_no'";
$query_fetch = $db->query($sql_fetch);
$row_fetch = $query_fetch->fetch_assoc();

$date=date('d-m-Y', strtotime($row_fetch['pr_date']));
//Include Master

$name = "Proforma_Invoice_AICPI-".substr($pr_no,7,3)."_".str_replace('-','',$date).".pdf";

include("token.php");
// echo $name;

$url = 'https://crm.ammarindustrial.in/assets/pdf/proforma_invoice/'.$name;

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
    $post_url .= '&type=media';
    $post_url .= '&message='."";
    $post_url .= '&media_url='.$url;
    $post_url .= '&filename='.$name;
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
}
$validator = array("success"=>true, "result"=>$result_image, "filename"=>$name);

echo json_encode($validator);

?>