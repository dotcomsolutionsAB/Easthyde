<?php
//ini_set('display_errors',1);
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
		$this->Cell(190,8,'M. M. Lucky Enterprise',0,1,C);
        $this->SetFont('Arial','',9);

		$this->Cell(190,4,'26 Starnd Road, Ground Floor',0,1,C);
		$this->Cell(190,4,'Kolkata - 700 001, West Bengal, India',0,1,C);
		$this->Cell(190,4,'Phone:+91 6289778473',0,1,C);
		$this->Cell(190,4,'Email:mmleind@gmail.com',0,1,C);

        $this->Cell(30,2,"",'',0,C);
        $this->Cell(130,2,"",'B',0,C);
		$this->Cell(30,2,"",'',1,C);
        $this->SetFont('Arial','B',10);
		$temp = $GLOBALS["supplier"];
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
                $char_space=($w-$this->cMargin*2-$str_width)/max(strlen($txt)-1,1)*$this->k;
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

//--------------------------------------------- Define Variables & Fetch Data from Database --------------------------------------
session_start();

$start = $_SESSION['start'];
$end = $_SESSION['end'];

$start_year = date('Y', strtotime($start));
$end_year = date('Y', strtotime($end));

$year = $start_year.'-'.substr($end, 2,2);

$date = date('Y-m-d',strtotime('today'));

if(strtotime($end) > strtotime($date)){
	$end = $date;
}

$id = $_REQUEST['id'];
$pdf_type = $_REQUEST['type'];

$result=array('particulars'=>array(),'date'=>array(),'voucher'=>array(),'credit'=>array(),'debit'=>array());


$sql_fetch = "SELECT * FROM suppliers WHERE id = '$id'";
$query_fetch = $db->query($sql_fetch);
$row_fetch = $query_fetch->fetch_assoc();

$supplier = $row_fetch['name'];
$supplier_email = $row_fetch['email'];

$new_opening_balance = json_decode($row_fetch['new_opening_balance'],true);
$len = sizeof($new_opening_balance['year']);

for($i=0;$i<$len;$i++)
{
    if($new_opening_balance['year'][$i] == $year)
    {
        $opening = $new_opening_balance['balance'][$i];
    }
}

$contacts = json_decode($row_fetch['contacts'], true);
$email=$contacts['email'][0];
$mobile=$contacts['mobile'][0];
$state = $row_fetch['state'];

$address = json_decode($row_fetch['address'], true);
$add_1=$address['address_1'];
$add_2=$address['address_2'];
$city=$address['city'];
$pincode=$address['pincode'];

$total=0;
$debit=0;
$credit=0;
$d_debit=0;
$c_credit=0;

if($opening != 0){
    $result['particulars'][] = 'Opening Balance';
    $result['date'][] = $start;
    $result['voucher'][] = '';
    $result['credit'][] = $opening;
    $result['debit'][] = '';
}

$sql = "SELECT * FROM purchase_invoice WHERE `supplier_name`='$supplier' AND `pi_date` BETWEEN '$start' AND '$end' ORDER BY `pi_date` ASC";
$query = $db->query($sql);

while ($row = $query->fetch_assoc()) {
    $count++;
    $tax_details = json_decode($row['tax'], true);
    $items_details = json_decode($row['items'], true);

    $total = $row['total'];
    $tax = $tax_details['cgst'] + $tax_details['sgst'] + $tax_details['igst'];
    
    

    // Add invoice details
    $result['particulars'][] = $row['pi_no'];
    $result['date'][] = $row['pi_date'];
    $result['voucher'][] = 'Purchase';
    $result['credit'][] = $total;
    $result['debit'][] = '';

    // // Now iterate over each item to add item details separately
    $item_names = $items_details['product'];
    $item_prices = $items_details['price'];
    $item_quantities = $items_details['quantity'];
    $item_discounts = $items_details['discount'];

    for ($i = 0; $i < count($item_names); $i++) {
        $name = $item_names[$i];
        $price = floatval($item_prices[$i]);
        $quantity = intval($item_quantities[$i]);
        $discount = floatval($item_discounts[$i]);

        // Calculate amount after discount
        $amount = ($price * $quantity) - (($price * $quantity) * ($discount / 100));

        // Add item details, leave other columns blank
        $result['particulars'][] = $name . " - Price: " . number_format($price, 2) . " - Quantity: $quantity - Amount: " . number_format($amount, 2);
        $result['date'][] = $row['pi_date'];
        $result['voucher'][] = '';
        $result['credit'][] = '';
        $result['debit'][] = '';
    }
}



$sql = "SELECT * FROM payments WHERE `supplier` = '$supplier' AND `date` BETWEEN '$start' AND '$end' ORDER BY `date` ASC";
$query = $db->query($sql);

while ($row = $query->fetch_assoc()) {
    // Decode the JSON stored in 'purchase_invoice'
    $purchase_invoice = json_decode($row['purchase_invoice'], true);

    // Check if 'pi_no' exists and is an array. If so, concatenate all elements with commas.
    $purchase_invoice_no = isset($purchase_invoice['pi_no']) ? implode(', ', $purchase_invoice['pi_no']) : 'N/A';
    
    // Prepare the result data
    $result['particulars'][] = 'Payment - ' . $row['mode'] . ' (' . $row['instrument'] . ') - PI #: ' . $purchase_invoice_no;
    $result['date'][]        = $row['date'];
    $result['voucher'][]     = 'Payment';
    $result['credit'][]      = '0';
    $result['debit'][]       = $row['amount'];
}




$len = sizeof($result['date']);

for($m=0;$m<$len-1;$m++){
    for($n=$m+1;$n<$len;$n++){
        if($result['date'][$m] > $result['date'][$n]){
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

$GLOBALS["supplier"] 	= $supplier;
$GLOBALS["start"] 	= date('d-m-Y', strtotime($start));
$GLOBALS["end"] 	= date('d-m-Y', strtotime($end));
$GLOBALS["add_1"]   = $add_1;
$GLOBALS["add_2"]   = $add_2;
$GLOBALS["city"]    = $city;
$GLOBALS["pincode"] = $pincode;
$GLOBALS["state"]   = $state;

$pdf = new PDF_AutoPrint();
$pdf->SetAutoPageBreak(true, 35);
$pdf->setMargins(10, 10);
$title = "Supplier Ledger";
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
    if (strpos($result['particulars'][$i], 'Price:') !== false) {
        // Set font to smaller and italic for item details
        $pdf->SetFont('Arial', 'I', 8); // 'I' for italic, 8 for smaller font size
    } else {
        // Set font to regular for other details
        $pdf->SetFont('Arial', 'B', 10);
    }
    $pdf->Cell(30,6,date('d-m-Y',strtotime($result['date'][$i])),'',0,C);
    $pdf->CellFitScale(80,6,$result['particulars'][$i],'',0,L);
    $pdf->Cell(20,6,$result['voucher'][$i],'',0,L);
    $pdf->Cell(30,6,money_format('%!i', $result['debit'][$i]),'',0,C);
    $pdf->Cell(30,6,money_format('%!i', $result['credit'][$i]),'',1,C);

    $total=$total+$result['credit'][$i]-$result['debit'][$i];
    $debit=$debit+$result['debit'][$i];
    $credit=$credit+$result['credit'][$i];
}
$pdf->SetFont('Arial','',9);
$pdf->Cell(30,6,'','',0,C);
$pdf->Cell(80,6,'','',0,R);
$pdf->Cell(20,6,'','',0,C);
$pdf->Cell(30,6,money_format('%!i',$debit),'T',0,C);
$pdf->Cell(30,6,money_format('%!i',$credit),'T',1,C);

$c_credit = $credit;
$d_debit = $debit;

if($total > 0){
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(30,6,'','',0,C);
    $pdf->Cell(80,6,'Closing Balance','',0,R);
    $pdf->Cell(20,6,'','',0,C);
    $pdf->Cell(30,6,money_format('%!i', $total),'',0,C);
    $pdf->Cell(30,6,'','',1,C);
    $d_debit += $total;
}else if ($total < 0){
    $total *= -1;
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(30,6,'','',0,C);
    $pdf->Cell(80,6,'Closing Balance','',0,R);
    $pdf->Cell(20,6,'','',0,C);
    $pdf->Cell(30,6,'','',0,C);
    $pdf->Cell(30,6,money_format('%!i', $total),'',1,C);
    $c_credit += $total;
}else{
    $pdf->Cell(128,1,'','',1,C);
}

$pdf->SetFont('Arial','B',9);
$pdf->Cell(30,6,'','',0,C);
$pdf->Cell(80,6,'','',0,R);
$pdf->Cell(20,6,'','',0,C);
$pdf->Cell(30,6,money_format('%!i',$d_debit),'TB',0,C);
$pdf->Cell(30,6,money_format('%!i',$c_credit),'TB',1,C);

$name = $supplier.'_'.str_replace('-','',$end).".pdf";

if($pdf_type == ''){
    $pdf->output('I',$name);
}else if ($pdf_type == 'email'){
    $sql_website = "SELECT * FROM email_settings";
    $query_website = $db->query($sql_website);
    $row_website = $query_website->fetch_assoc();

    $sending_host = $row_website['sending_host'];
    $sending_email = $row_website['sending_email'];
    $sending_email_password = $row_website['sending_email_password'];

    $filename = $name;

    $attachment= $pdf->output($rname, 'S');

    $email_arr = trim($supplier_email);
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
