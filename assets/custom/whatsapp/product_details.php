<?php
// ini_set('display_errors', 1);
//Head

session_start();
require_once "../connect.php";
include("token.php");


$id = $_REQUEST['pd_whatsapp_id'] ?? '';
$mobile = $_REQUEST['pd_whatsapp_number'] ?? '';

$wa_msg = $_REQUEST['pd_whatsapp_message'] ?? '';
$technical_pdf = $_REQUEST['technical_pdf'] ?? '';

$flag = 0;

if($technical_pdf != '' && $technical_pdf != '0')
{
    $flag = 1;  
}

$images = $_REQUEST['images'] ?? [];

$len = is_array($images) ? sizeof($images) : 0;

$sql_fetch = "SELECT * FROM product WHERE id = '$id'";
$query_fetch = $db->query($sql_fetch);
$row_fetch = ($query_fetch) ? $query_fetch->fetch_assoc() : null;

$product_name = $row_fetch['name'];
$group = $row_fetch['group'];
$group = str_replace(" ","_",$group);

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
    $post_url .= '&message='.urlencode($wa_msg);
    $post_url .= '&type=text';
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

    // Whatsapp Images

    foreach ((is_array($_REQUEST['images'] ?? null) ? $_REQUEST['images'] : []) as $image)
    {
        // Whatsapp
        $post_url = $woonotif_url;
        $post_url .= 'number='.$mob_no;
        $post_url .= '&type=media';
        $post_url .= '&message='."";
        $post_url .= '&media_url='.urlencode($image);
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

    // Whatsapp PDF
    if($flag == 1)
    {
        // Whatsapp
        $post_url = $woonotif_url;
        $post_url .= 'number='.$mob_no;
        $post_url .= '&type=media';
        $post_url .= '&message='."";
        $post_url .= '&media_url='.$technical_pdf;
        $post_url .= '&filename=technical_pdf.pdf';
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
        $result = curl_exec($ch_image);
    }

    if(($_SESSION['pd_whatsapp'] ?? '') != '')
    {
        $files = rtrim($_SESSION['pd_whatsapp'] ?? '',',');
        $files_arr = explode(',', $files);

        $files_len = is_array($files_arr) ? sizeof($files_arr) : 0;

        for($fi=0;$fi<$files_len;$fi++){
            $url = 'https://crm.ammarindustrial.in/assets/uploads/pd_whatsapp/'.$files_arr[$fi];

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
                $post_url .= '&filename='.$files_arr[$fi];
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
        }
    }
    $_SESSION['pd_whatsapp'] = '';
}

echo json_encode($result);

?>