<?php
// ini_set('display_errors', 1);
//Head

session_start();
require_once "../connect.php";

$mobile             = $_REQUEST['whatsapp_no'] ?? '';
$wa_msg             = $_REQUEST['whatsapp_messaage'] ?? '';

include("token.php");


$numbers = explode(',',$mobile);
$length = is_array($numbers) ? sizeof($numbers) : 0;

if(($_SESSION['filename'] ?? '') != '')
{
    $files = rtrim($_SESSION['filename'] ?? '',',');
    $files_arr = explode(',', $files);

    $files_len = is_array($files_arr) ? sizeof($files_arr) : 0;

    for($fi=0;$fi<$files_len;$fi++){
        $url = 'https://crm.ammarindustrial.in/assets/uploads/whatsapp/'.$files_arr[$fi];

        $numbers = explode(',',$mobile);
        $length = is_array($numbers) ? sizeof($numbers) : 0;
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
            $post_url .= '&instance_id='.$instance_id;
            $post_url .= '&access_token='.$token;    

            $ch = curl_init();
            curl_setopt_array($ch, array(
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
            $result_image = curl_exec($ch);
        }
    }
}


$_SESSION['filename'] = '';

$numbers = explode(',',$mobile);
$length = is_array($numbers) ? sizeof($numbers) : 0;

for($i=0;$i<$length;$i++){

    $mob_no = $numbers[$i];

    if(strlen($mob_no) == '10'){
        $mob_no = '91'.$mob_no;
    }
    
    // Whatsapp
    $post_url = $woonotif_url;
    $post_url .= 'number='.$mob_no;
    $post_url .= '&type=text';
    $post_url .= '&message='.urlencode($wa_msg);
    $post_url .= '&instance_id='.$instance_id;
    $post_url .= '&access_token='.$token;    

    $ch = curl_init();
    curl_setopt_array($ch, array(
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
    $result = curl_exec($ch);
}

$temp = $post_url;


$validator = array("success"=>true, "result"=>$result, "request"=>$temp);

echo json_encode($validator);

?>