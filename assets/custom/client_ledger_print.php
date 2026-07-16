<?php
//ini_set("display_errors",1);

require('pdf_js.php');
include ("connect.php");
session_start();
setlocale(LC_MONETARY, 'en_IN');

use PHPMailer\PHPMailer\PHPMailer;
require '../plugins/custom/PHPMailer/src/PHPMailer.php';
require '../plugins/custom/PHPMailer/src/SMTP.php';
require '../plugins/custom/PHPMailer/src/Exception.php';

class PDF_AutoPrint extends PDF_JavaScript
{
    
    function AutoPrint($printer='')
    {
        // Open the print dialog
        if($printer)
        {
            $printer = str_replace('\\', '\\\\', $printer);
            $script = "var pp = getPrintParams();";
            $script .= "pp.interactive = pp.constants.interactionLevel.full;";
            $script .= "pp.printerName = '$printer'";
            $script .= "print(pp);";
        }
        else
            $script = 'print(true);';
        $this->IncludeJS($script);
    }
    // Page header
	function Header()
	{
		// echo '<script>console.log("'.$GLOBALS["dt"].'"); </script>';
		$this->SetDrawColor(0,0,0);
		$this->Rect(8, 8, 194, 281, 'D');

		$this->SetFont('Arial','B',18);
		$this->Cell(190,3,'',0,2,C);
		$this->Cell(190,8,'M.M. Lucky Enterprise',0,1,C);
        $this->SetFont('Arial','',9);

		$this->Cell(190,4,'26, Strand Road, Ground Floor',0,1,C);
		$this->Cell(190,4,'Kolkata - 700 001, West Bengal, India',0,1,C);
		$this->Cell(190,4,'Phone:+91 6289778473',0,1,C);
		$this->Cell(190,4,'Email:mmleind@gmail.com',0,1,C);

        $this->Cell(30,2,"",'',0,C);
        $this->Cell(130,2,"",'B',0,C);
		$this->Cell(30,2,"",'',1,C);
        $this->SetFont('Arial','B',10);
		$temp = $GLOBALS["client"];
		$this->Cell(190,7,$temp,0,1,C);
        $this->SetFont('Arial','',8);

        $this->Cell(190,4,$GLOBALS["add_1"].' '.$GLOBALS["add_2"],0,1,C);
        $this->Cell(190,4,$GLOBALS["city"].' - '.$GLOBALS["pincode"],0,1,C);
        $this->Cell(190,4,$GLOBALS["state"],0,1,C);

        $this->Cell(190,4,'',0,1,C);
        $this->Cell(190,4,'Ledger Account',0,1,C);
		$temp = $GLOBALS["start"].' - '.$GLOBALS["end"];
		$this->Cell(190,4,$temp,0,1,C);
	}

	// Page footer
	function Footer()
	{

	    $this->SetY(-15);
	    // // Arial italic 8
	    $this->SetFont('Arial','I',8);
	    // // Page number
	    $this->Cell(190,20,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	    // $this->Cell(20,20,'',0,0,'C');
	    // $this->Cell(128,20,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}

	//Cell with horizontal scaling if text is too wide
    function CellFit($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='', $scale=false, $force=true)
    {
        //Get string width
        $str_width=$this->GetStringWidth($txt);

        //Calculate ratio to fit cell
        if($w==0)
            $w = $this->w-$this->rMargin-$this->x;
        $ratio = ($w-$this->cMargin*2)/$str_width;

        $fit = ($ratio < 1 || ($ratio > 1 && $force));
        if ($fit)
        {
            if ($scale)
            {
                //Calculate horizontal scaling
                $horiz_scale=$ratio*100.0;
                //Set horizontal scaling
                $this->_out(sprintf('BT %.2F Tz ET',$horiz_scale));
            }
            else
            {
                //Calculate character spacing in points
                $char_space=($w-$this->cMargin*2-$str_width)/max(strlen((string)$txt)-1,1)*$this->k;
                //Set character spacing
                $this->_out(sprintf('BT %.2F Tc ET',$char_space));
            }
            //Override user alignment (since text will fill up cell)
            $align='';
        }

        //Pass on to Cell method
        $this->Cell($w,$h,$txt,$border,$ln,$align,$fill,$link);

        //Reset character spacing/horizontal scaling
        if ($fit)
            $this->_out('BT '.($scale ? '100 Tz' : '0 Tc').' ET');
    }

    //Cell with horizontal scaling only if necessary
    function CellFitScale($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,true,false);
    }

    //Cell with horizontal scaling always
    function CellFitScaleForce($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,true,true);
    }

    //Cell with character spacing only if necessary
    function CellFitSpace($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,false,false);
    }

    //Cell with character spacing always
    function CellFitSpaceForce($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        //Same as calling CellFit directly
        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,false,true);
    }

}

//-------------------------------------- Define Variables & Fetch Data from Database -----------------------------------
session_start();

$start = $_SESSION['start'] ?? '';
$end = $_SESSION['end'] ?? '';


$start_month = date('m', strtotime($start));
if($start_month > 3 && $start_month <= 12)
{
    $start_year = date('Y', strtotime($start));
    $end_year = $start_year + 1;
}
else{
    $start_year = date('Y', strtotime($start)) - 1;
    $end_year = date('Y', strtotime($start));
}

$year = $start_year.'-'.substr($end_year, 2,2);

$date = date('Y-m-d',strtotime('today'));

if(strtotime($end) > strtotime($date)){
	$end = $date;
}

$id = $_REQUEST['id'] ?? '';
//echo $id;

$pdf_type = $_REQUEST['type'] ?? '';
//echo $pdf_type;
$series_condition = ($pdf_type == 'all') ? "%" : $pdf_type ;
//echo 'hi'.$series_condition;

$result=array('particulars'=>array(),'date'=>array(),'voucher'=>array(),'credit'=>array(),'debit'=>array());

$sql_fetch = "SELECT * FROM clients WHERE id = '$id'";
$query_fetch = $db->query($sql_fetch);
$row_fetch = $query_fetch ? $query_fetch->fetch_assoc() : null;
if (!$row_fetch) {
	exit('Record not found');
}

$client = $row_fetch['name'] ?? '';
$client_email = $row_fetch['email'] ?? '';
$client_email = 'kburhanuddin12@gmail.com';



$contacts = json_decode($row_fetch['contacts'] ?? '', true);
if (!is_array($contacts)) {
	$contacts = [];
}
$email = $contacts['email'][0] ?? '';
$mobile = $contacts['mobile'][0] ?? '';
$state = $row_fetch['state'] ?? '';

$address = json_decode($row_fetch['address'] ?? '', true);
if (!is_array($address)) {
	$address = [];
}
$add_1 = $address['address_1'] ?? '';
$add_2 = $address['address_2'] ?? '';
$city = $address['city'] ?? '';
$pincode = $address['pincode'] ?? '';

$opening = 0;

$new_opening_balance = json_decode($row_fetch['new_opening_balance'] ?? '', true);
if (!is_array($new_opening_balance)) {
	$new_opening_balance = [];
}
$len = is_array($new_opening_balance['year'] ?? null) ? count($new_opening_balance['year']) : 0;

for($i=0;$i<$len;$i++)
{
    if(($new_opening_balance['year'][$i] ?? '') == $year)
    {
        $opening = $new_opening_balance['balance'][$i] ?? 0;
    }
}

$email = isset($contacts['email'][0]) ? $contacts['email'][0] : '';
$mobile = isset($contacts['mobile'][0]) ? $contacts['mobile'][0] : '';

$count = 1;
$total = 0;
$debit = 0;
$credit = 0;
$d_debit = 0;
$c_credit = 0;
$temp_credit = 0;
$temp_debit = 0;
$count = 0;

$sql = "SELECT * FROM sales_invoice WHERE client_name = ? AND si_date BETWEEN ? AND ? AND cancelled = 0 ORDER BY si_date ASC";
$stmt = $db->prepare($sql);
if (!$stmt) {
    error_log("Sales invoice query prepare failed: " . $db->error);
    exit("Database error.");
}
$stmt->bind_param("sss", $client, $start, $end);
$stmt->execute();
$query = $stmt->get_result();

while ($row = $query->fetch_assoc()) {
    $count++;
    $tax_details = json_decode($row['tax'] ?? '', true);
    if (!is_array($tax_details)) {
        $tax_details = [];
    }

    $total = $row['total'];
    $tax = (isset($tax_details['cgst']) ? $tax_details['cgst'] : 0) +
          (isset($tax_details['sgst']) ? $tax_details['sgst'] : 0) +
          (isset($tax_details['igst']) ? $tax_details['igst'] : 0);

    $result['particulars'][] = $row['si_no'];
    $result['date'][] = $row['si_date'];
    $result['voucher'][] = 'Sales';
    $result['credit'][] = '';
    $result['debit'][] = $total;
}
$stmt->close();



$sql = "SELECT * FROM receipts WHERE client = ? AND date BETWEEN ? AND ? ORDER BY date ASC";
$stmt = $db->prepare($sql);
if (!$stmt) {
    error_log("Receipts query prepare failed: " . $db->error);
    exit("Database error.");
}
$stmt->bind_param("sss", $client, $start, $end);
$stmt->execute();
$query = $stmt->get_result();

while ($row = $query->fetch_assoc()) {
    // Decode the JSON stored in 'sales_invoice'
    $sales_invoice = json_decode($row['sales_invoice'] ?? '', true);
    if (!is_array($sales_invoice)) {
        $sales_invoice = [];
    }
    
    // Check if 'si_no' exists and is an array, then concatenate all elements with commas
    $si_no = isset($sales_invoice['si_no']) ? implode(', ', $sales_invoice['si_no']) : 'N/A';

    // Store the SI numbers in the particulars
    $result['particulars'][] = 'Payment - ' . htmlspecialchars((string)($row['mode'] ?? ''), ENT_QUOTES, 'UTF-8') . ' (' . htmlspecialchars((string)($row['instrument'] ?? ''), ENT_QUOTES, 'UTF-8') . ') - SI #: ' . $si_no;
    $result['date'][] = $row['date'];
    $result['voucher'][] = 'Receipt';
    $result['credit'][] = $row['amount'];
    $result['debit'][] = '0';
}
$stmt->close();

$len = count($result['date']);

for ($m = 0; $m < $len - 1; $m++) {
    for ($n = $m + 1; $n < $len; $n++) {
        if ($result['date'][$m] > $result['date'][$n]) {
            $temp = $result['date'][$m];
            $result['date'][$m] = $result['date'][$n];
            $result['date'][$n] = $temp;

            $temp = $result['particulars'][$m];
            $result['particulars'][$m] = $result['particulars'][$n];
            $result['particulars'][$n] = $temp;

            $temp = $result['voucher'][$m];
            $result['voucher'][$m] = $result['voucher'][$n];
            $result['voucher'][$n] = $temp;

            $temp = $result['credit'][$m];
            $result['credit'][$m] = $result['credit'][$n];
            $result['credit'][$n] = $temp;

            $temp = $result['debit'][$m];
            $result['debit'][$m] = $result['debit'][$n];
            $result['debit'][$n] = $temp;
        }
    }
}

$GLOBALS["client"] 	= $client;
$GLOBALS["start"] 	= !empty($start) ? date('d-m-Y', strtotime($start)) : '';
$GLOBALS["end"] 	= !empty($end) ? date('d-m-Y', strtotime($end)) : '';
$GLOBALS["add_1"]   = $add_1;
$GLOBALS["add_2"]   = $add_2;
$GLOBALS["city"]    = $city;
$GLOBALS["pincode"] = $pincode;
$GLOBALS["state"]   = $state;

$pdf = new PDF_AutoPrint();
$pdf->SetAutoPageBreak(true, 35);
$pdf->setMargins(10, 10);
$title = "Client Ledger";
$pdf->SetTitle($title);

$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->setX('10');

//----------------------------------------------- Table Header -----------------------------------------------
$y = $pdf->getY();

$pdf->Ln();

$pdf->SetFont('Arial','B',9);
$pdf->Cell(30,6,'DATE','TB',0,C);
$pdf->Cell(80,6,'PARTICULARS','TB',0,C);
$pdf->Cell(20,6,'VCH TYPE','TB',0,C);
$pdf->Cell(30,6,'DEBIT','TB',0,C);
$pdf->Cell(30,6,'CREDIT','TB',1,C);

$count = 1;
$total=0;
$debit=0;
$credit=0;
$len = sizeof($result['particulars']);
for($i=0;$i<$len;$i++){ 
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(30,6,!empty($result['date'][$i]) ? date('d-m-Y',strtotime($result['date'][$i])) : '','',0,C);
    $pdf->CellFitScale(80,6,$result['particulars'][$i],'',0,L);
    $pdf->Cell(20,6,$result['voucher'][$i],'',0,C);
    $pdf->Cell(30,6,number_format((float)$result['debit'][$i], 2),'',0,C);
    $pdf->Cell(30,6,number_format((float)$result['credit'][$i], 2),'',1,C);

    $total=$total+$result['credit'][$i]-$result['debit'][$i];
    $debit=$debit+$result['debit'][$i];
    $credit=$credit+$result['credit'][$i];
}
$pdf->SetFont('Arial','',9);
$pdf->Cell(30,6,'','',0,C);
$pdf->Cell(80,6,'','',0,R);
$pdf->Cell(20,6,'','',0,C);
$pdf->Cell(30,6,number_format((float)$debit, 2),'T',0,C);
$pdf->Cell(30,6,number_format((float)$credit, 2),'T',1,C);

$c_credit = $credit;
$d_debit = $debit;

if($total > 0){
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(30,6,'','',0,C);
    $pdf->Cell(80,6,'Closing Balance','',0,R);
    $pdf->Cell(20,6,'','',0,C);
    $pdf->Cell(30,6,number_format((float)$total, 2),'',0,C);
    $pdf->Cell(30,6,'','',1,C);
    $d_debit += $total;
}else if ($total < 0){
    $total *= -1;
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(30,6,'','',0,C);
    $pdf->Cell(80,6,'Closing Balance','',0,R);
    $pdf->Cell(20,6,'','',0,C);
    $pdf->Cell(30,6,'','',0,C);
    $pdf->Cell(30,6,number_format((float)$total, 2),'',1,C);
    $c_credit += $total;
}else{
    $pdf->Cell(128,1,'','',1,C);
}

$pdf->SetFont('Arial','B',9);
$pdf->Cell(30,6,'','',0,C);
$pdf->Cell(80,6,'','',0,R);
$pdf->Cell(20,6,'','',0,C);
$pdf->Cell(30,6,number_format((float)$d_debit, 2),'TB',0,C);
$pdf->Cell(30,6,number_format((float)$c_credit, 2),'TB',1,C);

$name = $client.'_'.str_replace('-','',$end).".pdf";

if($pdf_type == '' || true){
    $pdf->output('I',$name);
}else if ($pdf_type == 'email'){
    $sql_website = "SELECT * FROM email_settings";
    $query_website = $db->query($sql_website);
    $row_website = $query_website ? $query_website->fetch_assoc() : null;
    if (!$row_website) {
        exit('Record not found');
    }

    $sending_host = $row_website['sending_host'];
    $sending_email = $row_website['sending_email'];
    $sending_email_password = $row_website['sending_email_password'];

    $filename = $name;

    $attachment= $pdf->output($rname, 'S');

    $email_arr = trim($client_email);
    $email_arr = str_replace(" ","",$email_arr);

    $email = explode(',', $email_arr);
    $len = sizeof($email);

    $subject = 'Ledger';
    $message = 'Please find the ledger statement attached to this email.';

    $validator = array('success' => false, 'message' => 'Message could not be sent. Mailer Error: {$mail->ErrorInfo}');

    //PHPMailer Object
    $mail = new PHPMailer(true); //Argument true in constructor enables exceptions

    $mail->isSMTP();
    $mail->Host = $sending_host;
    $mail->SMTPAuth = true;
    $mail->Username = $sending_email;
    $mail->Password = $sending_email_password;
    $mail->SMTPSecure = 'SSL';
    $mail->Port = 587;

    //From email address and name
    $mail->From = $sending_email;
    $mail->FromName = "Ammar Industrial Corporation";

    //To address and name
    for($k=0;$k<$len;$k++){
        $mail->addAddress($email[$k], '');     // Add a recipient
        // echo $email[$k];
    }

    // Address to which recipient will reply
    $mail->addReplyTo("info@ammarindustrial.in");

    //Send HTML or Plain Text email
    $mail->isHTML(true);

    $mail->AddStringAttachment($attachment, $filename);

    $mail->Subject = $subject;
    $mail->Body = $message;

    try {
        $mail->send();
        // $validator['save_mail_result'] = save_mail($mail);
        $validator['success'] = true;
        $validator['message'] = 'Successfully Sent';
    } catch (Exception $e) {
        $validator['success'] = false;
        $validator['message'] = "Mailer Error: " . $mail->ErrorInfo;
    }
    echo $validator['message'];
}


?>
