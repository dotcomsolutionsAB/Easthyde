<?php
require('pdf_js.php');
include ("connect.php");
session_start();
setlocale(LC_MONETARY, 'en_IN');

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
		
	 //    $this->Image("../media/pdf/quot_top.jpg",10,10,190,20);
		// $this->Cell(190,20,'','B',2,C);

		$this->SetFont('Arial','U',15);
		$this->Cell(190,3,'',0,2,C);
		$this->Cell(60,7,'',0,0,C);
		$this->Cell(70,7,'Tax Invoice',0,0,C);
		$this->SetFont('Arial','',8);
		$this->Cell(60,7,$GLOBALS['label'],0,1,R);

		$this->SetFont('Arial','B',20);
		$this->Cell(190,8,'M.M. Lucky Enterprise',0,2,C);
		$this->SetFont('Arial','',9);
		$this->Cell(190,4,'26, Strand Road, Ground Floor,',0,2,C);
		$this->Cell(190,4,'Kolkata - 700 001, West Bengal, India',0,2,C);
		//$this->Cell(190,4,'',0,1,C);
		$this->Cell(190,4,'Email:mmleind@gmail.com ',0,4,C);
		//$this->Cell(190,4,'',0,1,C);
		$this->Cell(190,4,'Phone:+91 6289778473 ',0,2,C);
		
		$this->SetFont('Arial','B',10);
		$this->Cell(70,4,$GLOBALS["vendor"],B,0,L);
		$this->Cell(120,4,'GST : 19ALCPM0139R1ZO',B,1,L);
		$this->SetFont('Arial','',8);
		$this->CellFitScale(190,4,'We value simplicity that\'s why we\'re cash-only. It helps us keep prices fair, service fast, and quality high. Just great products, at great value - paid with cash.','BLR',1,C);

	    $this->Image("../media/company-logos/logo.jpg",10,20,50,20);
		$this->Image("../media/company-logos/MSME1.png",165,18,30,25);
	    // $this->Image("../media/pdf/contact.jpg",10,40,3,3);
	    $this->Image("../media/pdf/email.jpg",80,36,3,3);
	    $this->Image("../media/pdf/whatsapp.jpg",83,40,3,3);

		$y = $this->getY();

		$this->SetFont('Arial','B',9);
		$this->Cell(90,2,'','R',2,L);
		$this->Cell(90,5,'Billing Details : ','R',2,L);
		$this->SetFont('Arial','B',9);
		$this->CellFitScale(90,5,$GLOBALS["client"] ?? '','R',2,L);
		$this->SetFont('Arial','',9);
		$this->CellFitScale(90,5,$GLOBALS["add1"] ?? '','R',2,L);
		$tmp = $GLOBALS["add2"] ?? '';
		$this->CellFitScale(90,5,$tmp,'R',2,L);
		$tmp = ($GLOBALS["city"] ?? '').' - '.($GLOBALS["pincode"] ?? '').', '.($GLOBALS["state"] ?? '').', '.($GLOBALS["country"] ?? '');
		$this->CellFitScale(90,5,$tmp,'R',2,L);
		$temp = 'GSTIN / UIN : '.($GLOBALS["gstin"] ?? '');
		$this->Cell(90,5,$temp,'RB',2,L);
		$this->SetFont('Arial','B',9);
		$this->Cell(90,5,'Shipping Details :','R',2,L);
		$this->SetFont('Arial','B',9);
		$this->CellFitScale(90,5,$GLOBALS["ship_client"] ?? '','R',2,L);
		$this->SetFont('Arial','',9);
		$this->CellFitScale(90,5,$GLOBALS["ship_add1"] ?? '','R',2,L);
		$tmp = $GLOBALS["ship_add2"] ?? '';
		$this->CellFitScale(90,5,$tmp,'R',2,L);
		$tmp = ($GLOBALS["ship_city"] ?? '').' - '.($GLOBALS["ship_pincode"] ?? '').', '.($GLOBALS["ship_state"] ?? '').', '.($GLOBALS["ship_country"] ?? '');
		$this->CellFitScale(90,5,$tmp,'R',2,L);
		$this->CellFitScale(90,6,$GLOBALS['mobile'] ?? '','RB',2,L);
		// $this->Cell(90,3,'','RB',2,L);

		$this->setXY('100',$y);

		$this->SetFont('Arial','',6);
		$this->Cell(50,3,'Invoice No.','R',0,L);
		$this->Cell(50,3,'Dated','',1,L);
		$this->setX('100');
		$this->SetFont('Arial','B',8);
		$this->CellFitScale(50,5,$GLOBALS["si_no"],'BR',0,L);
		$this->CellFitScale(50,5,$GLOBALS["dt"],'B',1,L);
		$this->setX('100');

		$this->SetFont('Arial','',6);
		$this->Cell(50,3,'Delivery Note','R',0,L);
		$this->Cell(50,3,'Mode/Terms of Payment','',1,L);
		$this->setX('100');
		$this->SetFont('Arial','B',8);
		$this->CellFitScale(50,5,'','BR',0,L);
		$this->CellFitScale(50,5,$GLOBALS['payment_terms'],'B',1,L);
		$this->setX('100');

		$this->SetFont('Arial','',6);
		$this->Cell(50,3,'Supplier\'s Reference','R',0,L);
		$this->Cell(50,3,'Other Reference (s)','',1,L);
		$this->setX('100');
		$this->SetFont('Arial','B',8);
		$this->CellFitScale(50,5,'','BR',0,L);
		$this->CellFitScale(50,5,$GLOBALS['other_ref'],'B',1,L);
		$this->setX('100');

		$this->SetFont('Arial','',6);
		$this->Cell(50,3,'Buyer\'s Order No','R',0,L);
		$this->Cell(50,3,'Dated','',1,L);
		$this->setX('100');
		$this->SetFont('Arial','B',8);
		$this->CellFitScale(50,5,$GLOBALS['buyer_order'],'BR',0,L);
		$this->CellFitScale(50,5,$GLOBALS['order_date'],'B',1,L);
		$this->setX('100');

		$this->SetFont('Arial','',6);
		$this->Cell(50,3,'Despatch Document No.','R',0,L);
		$this->Cell(50,3,'Delivery Note Date','',1,L);
		$this->setX('100');
		$this->SetFont('Arial','B',8);
		$this->CellFitScale(50,5,$GLOBALS['despatch_doc_no'],'BR',0,L);
		$this->CellFitScale(50,5,$GLOBALS['despatch_date'],'B',1,L);
		$this->setX('100');

		$this->SetFont('Arial','',6);
		$this->Cell(50,3,'Despatched Through','R',0,L);
		$this->Cell(50,3,'Destination','',1,L);
		$this->setX('100');
		$this->SetFont('Arial','B',8);
		$this->CellFitScale(50,5,$GLOBALS['despatch_medium'],'BR',0,L);
		$this->CellFitScale(50,5,$GLOBALS['despatch_destination'],'B',1,L);
		$this->setX('100');

		$this->SetFont('Arial','',6);
		$this->Cell(100,4,'Terms of Delivery','',1,L);
		$this->setX('100');
		$this->SetFont('Arial','B',8);
		$this->CellFitScale(100,5,$GLOBALS['delivery_terms'],'',1,L);
		$this->setX('100');
		$this->Cell(100,5,'','',1,L);
		
	    
	    
	    
		
		$y = $this->getY();
		$this->setXY('100',$y);
		$this->Cell(50,1,'','B',0,L);
		$this->Cell(50,1,'','B',1,L);
	}

	// Page footer
	function Footer()
	{

		$this->Image("../media/pdf/quot_bottom.jpg",9,259,192,29);
		$this->Line(10,258,200,258);
	    // Position at 1.5 cm from bottom
	    $this->SetY(-17);
	    // Arial italic 8
	    $this->SetFont('Arial','I',8);
	    // Page number
	    // $this->Cell(0,20,'Page '.$GLOBALS["pages"],0,0,'C');
	}

	//Cell with horizontal scaling if text is too wide
    function CellFit($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='', $scale=false, $force=true)
    {
        $txt = (string)($txt ?? '');
        //Get string width
        $str_width=$this->GetStringWidth($txt);

        if($str_width == 0 || $str_width == null)
        	$str_width = 1;

        //Calculate ratio to fit cell
        if($w==0)
            $w = $this->w-$this->rMargin-$this->x;

		if($str_width > 0)
        	$ratio = ($w-$this->cMargin*2)/$str_width;
		else
			$ratio = 1;
		
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

$si_no = $_REQUEST['id'] ?? '';
$pdf_type = $_REQUEST['type'] ?? '';

$start = 1;
$copies = 1;
if(($_REQUEST['si_start'] ?? '') != '')
	$start = $_REQUEST['si_start'];
if(($_REQUEST['si_copies'] ?? '') != '')
	$copies = $_REQUEST['si_copies'];

$safe_id = $db->real_escape_string((string)$si_no);
if ($safe_id === '') {
	die('Missing invoice id. Open print from Sales Invoice list.');
}
// Accept either invoice number (si_no) or numeric DB id
if (ctype_digit($safe_id)) {
	$sql = "SELECT * FROM sales_invoice WHERE `si_no` = '$safe_id' OR `id` = '$safe_id' LIMIT 1";
} else {
	$sql = "SELECT * FROM sales_invoice WHERE `si_no` = '$safe_id' LIMIT 1";
}
$query = $db->query($sql);
if (!$query || !($row = $query->fetch_assoc())) {
	die('Record not found');
}
$si_no = $row['si_no'] ?? $si_no;

$show_hsn = $row['hsn_table'] ?? '';

$shipping = json_decode($row['shipping'] ?? '', true);
if (!is_array($shipping)) { $shipping = []; }

$client = $row['client_name'] ?? '';
$items = json_decode($row['items'] ?? '', true);
if (!is_array($items)) { $items = []; }
foreach (['product', 'group', 'desc', 'hsn', 'quantity', 'unit', 'price', 'discount', 'tax', 'cgst', 'sgst', 'igst'] as $__item_key) {
	if (!isset($items[$__item_key]) || !is_array($items[$__item_key])) {
		$items[$__item_key] = [];
	}
}
unset($__item_key);
$invoice_details = json_decode($row['invoice_details'] ?? '', true);
if (!is_array($invoice_details)) { $invoice_details = []; }

$sql_temp = "SELECT * FROM clients WHERE name = '$client'";
$query_temp = $db->query($sql_temp);
$row_temp = $query_temp ? $query_temp->fetch_assoc() : null;
$GLOBALS["vendor"]='';
if($row_temp && ($row_temp['vendor_code'] ?? null)!=null)
{
	$GLOBALS["vendor"] = "Vendor Code : ".$row_temp['vendor_code'];
}

$address = [];
if ($row_temp) {
	$address = json_decode($row_temp['address'] ?? '', true);
}
if (!is_array($address)) { $address = []; }

$GLOBALS["si_no"] = $si_no;
$GLOBALS["dt"] = !empty($row['si_date']) ? date('d-m-Y', strtotime($row['si_date'])) : '';

$GLOBALS['client'] = is_array($row_temp) ? ($row_temp['print_name'] ?? $client) : $client;
$GLOBALS['add1'] = $address["address_1"] ?? '';
$GLOBALS['add2'] = $address["address_2"] ?? '';
$GLOBALS['city'] = $address["city"] ?? '';
$GLOBALS['pincode'] = $address["pincode"] ?? '';
$GLOBALS['state'] = is_array($row_temp) ? ($row_temp["state"] ?? '') : '';
$GLOBALS['country'] = is_array($row_temp) ? ($row_temp["country"] ?? '') : '';
$GLOBALS['mobile'] = '';
if(($row["mobile"] ?? null)!=null && (string)$row["mobile"] !== '' && (string)$row["mobile"] !== '0'){
	$GLOBALS['mobile'] = "MOBILE No: ".$row["mobile"];
}

$GLOBALS['ship_client'] = $shipping['name'] ?? '';
$GLOBALS['ship_add1'] 	= $shipping["address_1"] ?? '';
$GLOBALS['ship_add2'] 	= $shipping["address_2"] ?? '';
$GLOBALS['ship_city'] 	= $shipping["city"] ?? '';
$GLOBALS['ship_pincode']= $shipping["pincode"] ?? '';
$GLOBALS['ship_state'] 	= $row["state"] ?? '';
$GLOBALS['ship_country']= $shipping["country"] ?? '';

$GLOBALS['buyer_order'] = $invoice_details["buyer_order"] ?? '';
if(($invoice_details["order_date"] ?? '') != '1970-01-01' && ($invoice_details["order_date"] ?? '') != '')
	$GLOBALS['order_date'] 		= date('d-m-Y', strtotime($invoice_details["order_date"]));
else
	$GLOBALS['order_date'] 		= '';
$GLOBALS['payment_terms'] = $invoice_details["payment_terms"] ?? '';
$GLOBALS['other_ref'] = $invoice_details["other_ref"] ?? '';
$GLOBALS['delivery_terms'] = $invoice_details["delivery_terms"] ?? '';


$GLOBALS['despatch_medium'] 	= $invoice_details["despatch_medium"] ?? '';
$GLOBALS['despatch_doc_no'] 	= $invoice_details["despatch_doc_no"] ?? '';
if(($invoice_details["despatch_date"] ?? '') != '1970-01-01' && ($invoice_details["despatch_date"] ?? '') != '')
	$GLOBALS['despatch_date'] 		= date('d-m-Y', strtotime($invoice_details["despatch_date"]));
else
	$GLOBALS['despatch_date'] 		= '';
$GLOBALS['despatch_destination']= $invoice_details["despatch_destination"] ?? '';

$GLOBALS['gstin'] = is_array($row_temp) ? ($row_temp["gstin"] ?? '') : '';
$GLOBALS['label'] = '';
$GLOBALS['pages'] = 1;
$GLOBALS['gross_total'] = 0;


$state_flag = 1;

if($GLOBALS['state'] == 'WEST BENGAL'){
	$state_flag = 0;
}

$pdf = new PDF_AutoPrint();
$pdf->SetAutoPageBreak(true, 35);
$pdf->setMargins(10, 10);
$title = "Tax Invoice";
$pdf->SetTitle($title);
$pdf->AliasNbPages();

$flag = 0;
$GLOBALS["label"] = "";

if($start != '0'){
	if($start == 1)
		$GLOBALS["label"] = "";
	else if($start == 2)
		$GLOBALS["label"] = "Original for Buyer";
	else if($start == 3)
		$GLOBALS["label"] = "Duplicate for Seller";
	else if($start == 4)
		$GLOBALS["label"] = "Triplicate for Transporter";
	else if($start == 5)
		$GLOBALS["label"] = "Extra Copy";
}else{
	// $copies = '3';
	$flag = 1;
}

for($ij=1;$ij<=$copies;$ij++){

	$GLOBALS["gross_total"] = '0';

	if($flag == '1'){
		if($ij == 1)
			$GLOBALS["label"] = "Original for Buyer";
		else if($ij == 2)
			$GLOBALS["label"] = "Duplicate for Seller";
		else if($ij == 3)
			$GLOBALS["label"] = "Triplicate for Transporter";
		else
			$GLOBALS["label"] = "Extra Copy";
	}


	// $pdf->AliasNbPages();
	$pdf->AddPage();
	$GLOBALS["pages"] = 1;
	
	

	$pdf->setX('10');

	//------------------------------------------------------ Table Header ---------------------------------------------------------------
	if($state_flag == 0)
	{
		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(7,4,'SN','R',0,C);
		$pdf->Cell(63,4,'DESCRIPTION OF GOODS','R',0,C);
		$pdf->Cell(10,4,'HSN','R',0,C);
		$pdf->Cell(10,4,'QTY','R',0,C);
		$pdf->Cell(10,4,'UNIT','R',0,C);
		$pdf->Cell(17,4,'RATE','R',0,C);
		$pdf->Cell(10,4,'DISC%','R',0,C);
		$pdf->Cell(8,4,'CGST','R',0,C);
		$pdf->Cell(12,4,'CGST','R',0,C);
		$pdf->Cell(8,4,'SGST','R',0,C);
		$pdf->Cell(12,4,'SGST','R',0,C);
		$pdf->Cell(23,4,'AMOUNT',0,1,C);

		$pdf->Cell(7,4,'','BR',0,C);
		$pdf->Cell(63,4,'','BR',0,C);
		$pdf->Cell(10,4,'','BR',0,C);
		$pdf->Cell(10,4,'','BR',0,C);
		$pdf->Cell(10,4,'','BR',0,C);
		$pdf->Cell(17,4,'','BR',0,C);
		$pdf->Cell(10,4,'','BR',0,C);
		$pdf->Cell(8,4,'Rate','BR',0,C);
		$pdf->Cell(12,4,'Amount','BR',0,C);
		$pdf->Cell(8,4,'Rate','BR',0,C);
		$pdf->Cell(12,4,'Amount','BR',0,C);
		$pdf->Cell(23,4,'(Rs)','B',1,C);
	}else{
		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(7,4,'SN','R',0,C);
		$pdf->Cell(63,4,'DESCRIPTION OF GOODS','R',0,C);
		$pdf->Cell(10,4,'HSN','R',0,C);
		$pdf->Cell(10,4,'QTY','R',0,C);
		$pdf->Cell(10,4,'UNIT','R',0,C);
		$pdf->Cell(17,4,'RATE','R',0,C);
		$pdf->Cell(10,4,'DISC%','R',0,C);
		$pdf->Cell(20,4,'IGST','R',0,C);
		$pdf->Cell(20,4,'IGST','R',0,C);
		$pdf->Cell(23,4,'AMOUNT',0,1,C);

		$pdf->Cell(7,4,'','BR',0,C);
		$pdf->Cell(63,4,'','BR',0,C);
		$pdf->Cell(10,4,'','BR',0,C);
		$pdf->Cell(10,4,'','BR',0,C);
		$pdf->Cell(10,4,'','BR',0,C);
		$pdf->Cell(17,4,'','BR',0,C);
		$pdf->Cell(10,4,'','BR',0,C);
		$pdf->Cell(20,4,'Rate','BR',0,C);
		$pdf->Cell(20,4,'Amount','BR',0,C);
		$pdf->Cell(23,4,'(Rs)','B',1,C);
	}

	$pdf->SetFont('Arial','',7);
	$grand_total_qty = 0;

	$tax_details = array('hsn'=>array(), 'rate'=>array(), 'taxable'=>array(), 'cgst'=>array(), 'sgst'=>array(), 'igst'=>array(), 'total'=>array());

	$l = is_array($items['product'] ?? null) ? sizeof($items['product']) : 0;

	//Printing All Items
	for($i=0;$i<$l;$i++){
		$pos = $i+1;
		//$pdf->Cell(7,4,'HIiiii','R',0,C);
		if($state_flag == 0)
		{
			$tax = ((float)($items['tax'][$i] ?? 0))/2;
			$cgst = (float)($items['cgst'][$i] ?? 0);
			$sgst = (float)($items['sgst'][$i] ?? 0);
			$pr = $items['product'][$i];
			$make = $items['group'][$i];
            
            if($items['discount'][$i] != '')
			    $line_total = $items['quantity'][$i]*$items['price'][$i]*(100-$items['discount'][$i])/100;
		    else
		        $line_total = $items['quantity'][$i]*$items['price'][$i];
		        
			$GLOBALS["gross_total"] += round($line_total,2);

			$sql_make = "SELECT * FROM product WHERE name = '$pr'";
			$query_make = $db->query($sql_make);
			$row_make = $query_make ? $query_make->fetch_assoc() : null;
			$pr_group = strtoupper((string)($row_make['group'] ?? ''));

			$temp = $items['desc'][$i];
			$product = dotcom_wordwrap($temp,50);
			$co = (is_array($product) ? count($product) : 1);

			if($make == '1')
				$temp = $items['product'][$i].', Make : '.$pr_group;
			else
				$temp = $items['product'][$i];

			$desc = dotcom_wordwrap($temp,50);
			$co_2 = count($desc);

			$description_array = explode('|', (string)($items['long_desc'][$i] ?? ''));
			$len = sizeof($description_array);

			$limit = $co * 5 + $co_2 * 5 ;

			$tmp_y = $pdf->getY();
			$product_limit = 297 - 35 - $limit;
			if($tmp_y > $product_limit){
				$GLOBALS["pages"]++;
				$pdf->AddPage();
				if($state_flag == 0)
				{
					$pdf->SetFont('Arial','B',7);
					$pdf->Cell(7,4,'SN','R',0,C);
					$pdf->Cell(63,4,'DESCRIPTION OF GOODS','R',0,C);
					$pdf->Cell(10,4,'HSN','R',0,C);
					$pdf->Cell(10,4,'QTY','R',0,C);
					$pdf->Cell(10,4,'UNIT','R',0,C);
					$pdf->Cell(17,4,'RATE','R',0,C);
					$pdf->Cell(10,4,'DISC%','R',0,C);
					$pdf->Cell(8,4,'CGST','R',0,C);
					$pdf->Cell(12,4,'CGST','R',0,C);
					$pdf->Cell(8,4,'SGST','R',0,C);
					$pdf->Cell(12,4,'SGST','R',0,C);
					$pdf->Cell(23,4,'AMOUNT',0,1,C);

					$pdf->Cell(7,4,'','BR',0,C);
					$pdf->Cell(63,4,'','BR',0,C);
					$pdf->Cell(10,4,'','BR',0,C);
					$pdf->Cell(10,4,'','BR',0,C);
					$pdf->Cell(10,4,'','BR',0,C);
					$pdf->Cell(17,4,'','BR',0,C);
					$pdf->Cell(10,4,'','BR',0,C);
					$pdf->Cell(8,4,'Rate','BR',0,C);
					$pdf->Cell(12,4,'Amount','BR',0,C);
					$pdf->Cell(8,4,'Rate','BR',0,C);
					$pdf->Cell(12,4,'Amount','BR',0,C);
					$pdf->Cell(23,4,'(Rs)','B',1,C);
				}else{
					$pdf->SetFont('Arial','B',7);
					$pdf->Cell(7,4,'SN','R',0,C);
					$pdf->Cell(63,4,'DESCRIPTION OF GOODS','R',0,C);
					$pdf->Cell(10,4,'HSN','R',0,C);
					$pdf->Cell(10,4,'QTY','R',0,C);
					$pdf->Cell(10,4,'UNIT','R',0,C);
					$pdf->Cell(17,4,'RATE','R',0,C);
					$pdf->Cell(10,4,'DISC%','R',0,C);
					$pdf->Cell(20,4,'IGST','R',0,C);
					$pdf->Cell(20,4,'IGST','R',0,C);
					$pdf->Cell(23,4,'AMOUNT',0,1,C);

					$pdf->Cell(7,4,'','BR',0,C);
					$pdf->Cell(63,4,'','BR',0,C);
					$pdf->Cell(10,4,'','BR',0,C);
					$pdf->Cell(10,4,'','BR',0,C);
					$pdf->Cell(10,4,'','BR',0,C);
					$pdf->Cell(17,4,'','BR',0,C);
					$pdf->Cell(10,4,'','BR',0,C);
					$pdf->Cell(20,4,'Rate','BR',0,C);
					$pdf->Cell(20,4,'Amount','BR',0,C);
					$pdf->Cell(23,4,'(Rs)','B',1,C);
				}
			}

			// Printing Name of the Product
			$pdf->Cell(7,5,$pos,'R',0,C);
			$pdf->SetFont('Arial','B',7);
			$pdf->CellFitScale(63,5,$desc[0],'R',0,L);
			$pdf->SetFont('Arial','',8);
			$pdf->CellFitScale(10,5,$items['hsn'][$i],'R',0,C);
			$pdf->CellFitScale(10,5,$items['quantity'][$i],'R',0,C);
			$pdf->CellFitScale(10,5,strtoupper((string)($items['unit'][$i] ?? '')),'R',0,C);
			if($items['price'][$i] > 0)
			{
				$pdf->CellFitScale(17,5,number_format((float)$items['price'][$i], 2),'R',0,R);
				if($items['discount'][$i] != '')
					$pdf->CellFitScale(10,5,number_format((float)$items['discount'][$i], 2),'R',0,C);
				else
					$pdf->CellFitScale(10,5,number_format((float)'0', 2),'R',0,C);
				$temp = $tax.' %';
				$pdf->Cell(8,5,$temp,'R',0,C);
				$pdf->CellFitScale(12,5,number_format((float)$cgst, 2),'R',0,C);
				$pdf->Cell(8,5,$temp,'R',0,C);
				$pdf->CellFitScale(12,5,number_format((float)$sgst, 2),'R',0,C);
			}else{
				$pdf->CellFitScale(17,5,'','R',0,R);
				$pdf->CellFitScale(10,5,'','R',0,R);
				$pdf->Cell(8,5,'','R',0,C);
				$pdf->CellFitScale(12,5,'','R',0,C);
				$pdf->Cell(8,5,'','R',0,C);
				$pdf->CellFitScale(12,5,'','R',0,C);
			}
			$pdf->CellFitScale(23,5,number_format((float)$line_total, 2),'',1,R);	

			if($co > 1){
				for( $z=1 ; $z<$co_2 ; $z++){
					$pdf->Cell(7,5,'','R',0,C);
					$pdf->SetFont('Arial','B',7);
					$pdf->CellFitScale(63,5,$desc[$z],'R',0,L);
					$pdf->SetFont('Arial','',7);
					$pdf->Cell(10,5,'','R',0,C);
					$pdf->Cell(10,5,'','R',0,C);
					$pdf->Cell(10,5,'','R',0,C);
					$pdf->Cell(17,5,'','R',0,R);
					$pdf->Cell(10,5,'','R',0,C);
					$pdf->Cell(8,5,'','R',0,C);
					$pdf->Cell(12,5,'','R',0,C);
					$pdf->Cell(8,5,'','R',0,C);
					$pdf->Cell(12,5,'','R',0,C);
					$pdf->Cell(23,5,'','',1,R);
					
				}
			}

			// Printing SKU & Make
			for( $z=0 ; $z<$co ; $z++){
				$pdf->Cell(7,5,'','R',0,C);
				$pdf->SetFont('Arial','I',7);
				$pdf->Cell(63,5,$product[$z],'R',0,L);
				$pdf->SetFont('Arial','',7);
				$pdf->Cell(10,5,'','R',0,C);
				$pdf->Cell(10,5,'','R',0,C);
				$pdf->Cell(10,5,'','R',0,C);
				$pdf->Cell(17,5,'','R',0,R);
				$pdf->Cell(10,5,'','R',0,C);
				$pdf->Cell(8,5,'','R',0,C);
				$pdf->Cell(12,5,'','R',0,C);
				$pdf->Cell(8,5,'','R',0,C);
				$pdf->Cell(12,5,'','R',0,C);
				$pdf->Cell(23,5,'','',1,R);
				
			}


			// Printing Description
			

		}else{
			$tax = (float)($items['tax'][$i] ?? 0);
			$igst = (float)($items['igst'][$i] ?? 0);
			$pr = $items['product'][$i];
			$make = $items['group'][$i];

			if($items['discount'][$i] != '')
			    $line_total = $items['quantity'][$i]*$items['price'][$i]*(100-$items['discount'][$i])/100;
		    else
		        $line_total = $items['quantity'][$i]*$items['price'][$i];
			$GLOBALS["gross_total"] += round($line_total,2);

			$sql_make = "SELECT * FROM product WHERE name = '$pr'";
			$query_make = $db->query($sql_make);
			$row_make = $query_make ? $query_make->fetch_assoc() : null;
			$pr_group = strtoupper((string)($row_make['group'] ?? ''));

			$temp = $items['desc'][$i];
			$product = dotcom_wordwrap($temp,50);
			$co = (is_array($product) ? count($product) : 1);

			if($make == '1')
				$temp = $items['product'][$i].', Make : '.$pr_group;
			else
				$temp = $items['product'][$i];

			$desc = dotcom_wordwrap($temp,50);
			$co_2 = count($desc);

			$description_array = explode('|', (string)($items['long_desc'][$i] ?? ''));
			$len = sizeof($description_array);

			$limit = $co * 4 + $co_2 * 5 ;


			$tmp_y = $pdf->getY();
			$product_limit = 297 - 35 - $limit;
			if($tmp_y > $product_limit){
				$GLOBALS["pages"]++;
				$pdf->AddPage();
				if($state_flag == 0)
				{
					$pdf->SetFont('Arial','B',7);
					$pdf->Cell(7,4,'SN','R',0,C);
					$pdf->Cell(63,4,'DESCRIPTION OF GOODS','R',0,C);
					$pdf->Cell(10,4,'HSN','R',0,C);
					$pdf->Cell(10,4,'QTY','R',0,C);
					$pdf->Cell(10,4,'UNIT','R',0,C);
					$pdf->Cell(17,4,'RATE','R',0,C);
					$pdf->Cell(10,4,'DISC%','R',0,C);
					$pdf->Cell(8,4,'CGST','R',0,C);
					$pdf->Cell(12,4,'CGST','R',0,C);
					$pdf->Cell(8,4,'SGST','R',0,C);
					$pdf->Cell(12,4,'SGST','R',0,C);
					$pdf->Cell(23,4,'AMOUNT',0,1,C);

					$pdf->Cell(7,4,'','BR',0,C);
					$pdf->Cell(63,4,'','BR',0,C);
					$pdf->Cell(10,4,'','BR',0,C);
					$pdf->Cell(10,4,'','BR',0,C);
					$pdf->Cell(10,4,'','BR',0,C);
					$pdf->Cell(17,4,'','BR',0,C);
					$pdf->Cell(10,4,'','BR',0,C);
					$pdf->Cell(8,4,'Rate','BR',0,C);
					$pdf->Cell(12,4,'Amount','BR',0,C);
					$pdf->Cell(8,4,'Rate','BR',0,C);
					$pdf->Cell(12,4,'Amount','BR',0,C);
					$pdf->Cell(23,4,'(Rs)','B',1,C);
				}else{
					$pdf->SetFont('Arial','B',7);
					$pdf->Cell(7,4,'SN','R',0,C);
					$pdf->Cell(63,4,'DESCRIPTION OF GOODS','R',0,C);
					$pdf->Cell(10,4,'HSN','R',0,C);
					$pdf->Cell(10,4,'QTY','R',0,C);
					$pdf->Cell(10,4,'UNIT','R',0,C);
					$pdf->Cell(17,4,'RATE','R',0,C);
					$pdf->Cell(10,4,'DISC%','R',0,C);
					$pdf->Cell(20,4,'IGST','R',0,C);
					$pdf->Cell(20,4,'IGST','R',0,C);
					$pdf->Cell(23,4,'AMOUNT',0,1,C);

					$pdf->Cell(7,4,'','BR',0,C);
					$pdf->Cell(63,4,'','BR',0,C);
					$pdf->Cell(10,4,'','BR',0,C);
					$pdf->Cell(10,4,'','BR',0,C);
					$pdf->Cell(10,4,'','BR',0,C);
					$pdf->Cell(17,4,'','BR',0,C);
					$pdf->Cell(10,4,'','BR',0,C);
					$pdf->Cell(20,4,'Rate','BR',0,C);
					$pdf->Cell(20,4,'Amount','BR',0,C);
					$pdf->Cell(23,4,'(Rs)','B',1,C);
				}
			}

			// Printing Name of the Product
			$pdf->Cell(7,5,$pos,'R',0,C);
			$pdf->SetFont('Arial','B',7);
			$pdf->CellFitScale(63,5,$desc[0],'R',0,L);
			$pdf->SetFont('Arial','',8);
			$pdf->CellFitScale(10,5,$items['hsn'][$i],'R',0,C);
			$pdf->CellFitScale(10,5,$items['quantity'][$i],'R',0,C);
			$pdf->CellFitScale(10,5,strtoupper((string)($items['unit'][$i] ?? '')),'R',0,C);
			if($items['price'][$i] > 0)
			{
				$pdf->CellFitScale(17,5,number_format((float)$items['price'][$i], 2),'R',0,R);
				if($items['discount'][$i] != '')
					$pdf->CellFitScale(10,5,number_format((float)$items['discount'][$i], 2),'R',0,C);
				else
					$pdf->CellFitScale(10,5,number_format((float)'0', 2),'R',0,C);
				$temp = $tax.' %';
				$pdf->Cell(20,5,$temp,'R',0,C);
				$pdf->CellFitScale(20,5,number_format((float)$igst, 2),'R',0,C);
			}else{
				$pdf->CellFitScale(17,5,'','R',0,R);
				$pdf->Cell(10,5,'','R',0,C);
				$pdf->CellFitScale(20,5,'','R',0,C);
				$pdf->Cell(20,5,'','R',0,C);
			}
			$pdf->CellFitScale(23,5,number_format((float)$line_total, 2),'',1,R);	

			if($co > 1){
				for( $z=1 ; $z<$co_2 ; $z++){
					$pdf->Cell(7,5,'','R',0,C);
					$pdf->SetFont('Arial','B',7);
					$pdf->CellFitScale(63,5,$desc[$z],'R',0,L);
					$pdf->SetFont('Arial','',7);
					$pdf->Cell(10,5,'','R',0,C);
					$pdf->Cell(10,5,'','R',0,C);
					$pdf->Cell(10,5,'','R',0,C);
					$pdf->Cell(17,5,'','R',0,R);
					$pdf->Cell(10,5,'','R',0,C);
					$pdf->Cell(20,5,'','R',0,C);
					$pdf->Cell(20,5,'','R',0,C);
					$pdf->Cell(23,5,'','',1,R);
					
				}
			}

			// Printing SKU & Make
			for( $z=0 ; $z<$co ; $z++){
				$pdf->Cell(7,5,'','R',0,C);
				$pdf->SetFont('Arial','I',7);
				$pdf->Cell(63,5,$product[$z],'R',0,L);
				$pdf->SetFont('Arial','',7);
				$pdf->Cell(10,5,'','R',0,C);
				$pdf->Cell(10,5,'','R',0,C);
				$pdf->Cell(10,5,'','R',0,C);
				$pdf->Cell(17,5,'','R',0,R);
				$pdf->Cell(10,5,'','R',0,C);
				$pdf->Cell(20,5,'','R',0,C);
				$pdf->Cell(20,5,'','R',0,C);
				$pdf->Cell(23,5,'','',1,R);
				
			}

			// Printing Description
			for($k=0;$k<$len;$k++){
				if($description_array[$k] != ''){
					$pdf->Cell(7,3,'','R',0,C);
					$pdf->SetFont('Arial','I',7);
					$temp = '     '.$description_array[$k];
					$pdf->Cell(63,3,$temp,'R',0,L);
					$pdf->SetFont('Arial','',7);
					$pdf->Cell(10,3,'','R',0,C);
					$pdf->Cell(10,3,'','R',0,C);
					$pdf->Cell(10,3,'','R',0,C);
					$pdf->Cell(17,3,'','R',0,R);
					$pdf->Cell(10,3,'','R',0,C);
					$pdf->Cell(20,3,'','R',0,C);
					$pdf->Cell(20,3,'','R',0,C);
					$pdf->Cell(23,3,'','',1,R);
				}
			}
		}

		$grand_total_qty+=$items['quantity'][$i];

		$hsn = $items['hsn'][$i];
		$pos = '-1';
		$len = sizeof($tax_details['hsn']);
		for($j=0;$j<$len;$j++){
			if($tax_details['hsn'][$j] == $hsn){
				$pos = $j;
				break;
			}
		}

		if($pos != '-1'){
			$tax_details['taxable'][$pos] += $line_total;
			$tax_details['cgst'][$pos] += (float)($items['cgst'][$i] ?? 0);
			$tax_details['sgst'][$pos] += (float)($items['sgst'][$i] ?? 0);
			$tax_details['igst'][$pos] += (float)($items['igst'][$i] ?? 0);
			$tax_details['total'][$pos] += (float)($items['cgst'][$i] ?? 0) + (float)($items['sgst'][$i] ?? 0) + (float)($items['igst'][$i] ?? 0);

		}else{
			$tax_details['hsn'][] = $items['hsn'][$i] ?? '';
			$tax_details['rate'][] = (float)($items['tax'][$i] ?? 0);
			$tax_details['taxable'][] = $line_total;
			$tax_details['cgst'][] = (float)($items['cgst'][$i] ?? 0);
			$tax_details['sgst'][] = (float)($items['sgst'][$i] ?? 0);
			$tax_details['igst'][] = (float)($items['igst'][$i] ?? 0);
			$tax_details['total'][] = (float)($items['cgst'][$i] ?? 0) + (float)($items['sgst'][$i] ?? 0) + (float)($items['igst'][$i] ?? 0);
		}

	}

	//Addons
	$pdf->Cell(120,3,'','TR',0,C);
	$pdf->Cell(47,5,'','LTR',0,L);
	$pdf->Cell(24,5,'','T',1,R);
	

	$addons_array = json_decode($row['addons'] ?? '', true);
	if (!is_array($addons_array)) { $addons_array = []; }
	if (!isset($addons_array['pf']) || !is_array($addons_array['pf'])) {
		$addons_array['pf'] = ['value' => '', 'cgst' => 0, 'sgst' => 0, 'igst' => 0];
	}
	if (!isset($addons_array['freight']) || !is_array($addons_array['freight'])) {
		$addons_array['freight'] = ['value' => '', 'cgst' => 0, 'sgst' => 0, 'igst' => 0];
	}
	if (!isset($addons_array['roundoff'])) { $addons_array['roundoff'] = ''; }

	$tmp_yy = $pdf->getY();

	$pdf->Cell(120,5,'','L',0,L);
	$pdf->SetFont('Arial','I',9);
	$pdf->Cell(47,5,'Gross Total','LR',0,L);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(23,5,number_format((float)$GLOBALS["gross_total"], 2),0,1,R);

	if(($addons_array['pf']['value'] ?? '')!='' && (float)($addons_array['pf']['value'] ?? 0) > 0){
		$pdf->Cell(120,5,'',0,0,L);
		$pdf->SetFont('Arial','I',9);
		$pdf->Cell(47,5,'Add   : Packaging & Forwarding','LR',0,L);
		$pdf->SetFont('Arial','',9);
		$pdf->Cell(23,5,number_format((float)$addons_array['pf']['value'], 2),0,1,R);

		$hsn = '99';
		$pos = '-1';
		$len = sizeof($tax_details['hsn']);
		for($j=0;$j<$len;$j++){
			if($tax_details['hsn'][$j] == $hsn){
				$pos = $j;
				break;
			}
		}

		if($pos != '-1'){
			$tax_details['taxable'][$pos] += $addons_array['pf']['value'];
			$tax_details['cgst'][$pos] += $addons_array['pf']['cgst'];
			$tax_details['sgst'][$pos] += $addons_array['pf']['sgst'];
			$tax_details['igst'][$pos] += $addons_array['pf']['igst'];
			$tax_details['total'][$pos] += $addons_array['pf']['cgst'] + $addons_array['pf']['sgst'] + $addons_array['pf']['igst'];

		}else{
			$tax_details['hsn'][] = $hsn;
			$tax_details['rate'][] = '18';
			$tax_details['taxable'][] = $addons_array['pf']['value'];
			$tax_details['cgst'][] = $addons_array['pf']['cgst'];
			$tax_details['sgst'][] = $addons_array['pf']['sgst'];
			$tax_details['igst'][] = $addons_array['pf']['igst'];
			$tax_details['total'][] = $addons_array['pf']['cgst'] + $addons_array['pf']['sgst'] + $addons_array['pf']['igst'];
		}
	}

	if(($addons_array['freight']['value'] ?? '')!='' && (float)($addons_array['freight']['value'] ?? 0) > 0){
		$pdf->Cell(120,5,'',0,0,L);
		$pdf->SetFont('Arial','I',9);
		$pdf->Cell(47,5,'Add   : Freight','LR',0,L);
		$pdf->SetFont('Arial','',9);
		$pdf->Cell(23,5,number_format((float)$addons_array['freight']['value'], 2),0,1,R);

		$hsn = '99';
		$pos = '-1';
		$len = sizeof($tax_details['hsn']);
		for($j=0;$j<$len;$j++){
			if($tax_details['hsn'][$j] == $hsn){
				$pos = $j;
				break;
			}
		}

		if($pos != '-1'){
			$tax_details['taxable'][$pos] += $addons_array['freight']['value'];
			$tax_details['cgst'][$pos] += $addons_array['freight']['cgst'];
			$tax_details['sgst'][$pos] += $addons_array['freight']['sgst'];
			$tax_details['igst'][$pos] += $addons_array['freight']['igst'];
			$tax_details['total'][$pos] += $addons_array['freight']['cgst'] + $addons_array['freight']['csgst']+ $addons_array['freight']['igst'];

		}else{
			$tax_details['hsn'][] = $hsn;
			$tax_details['rate'][] = '18';
			$tax_details['taxable'][] = $addons_array['freight']['value'];
			$tax_details['cgst'][] = $addons_array['freight']['cgst'];
			$tax_details['sgst'][] = $addons_array['freight']['sgst'];
			$tax_details['igst'][] = $addons_array['freight']['igst'];
			$tax_details['total'][] = $addons_array['freight']['cgst'] + $addons_array['freight']['sgst'] + $addons_array['freight']['igst'];
		}
	}

	$sgst = 0;
	$cgst = 0;
	$igst = 0;

	$len = sizeof($tax_details['hsn']);
	for($j=0;$j<$len;$j++){
		$sgst += $tax_details['sgst'][$j];
		$cgst += $tax_details['cgst'][$j];
		$igst += $tax_details['igst'][$j];
	}

	if($state_flag == '0'){
		$pdf->SetFont('Arial','I',9);
		$pdf->Cell(120,5,'',0,0,L);
		$pdf->Cell(47,5,'Add   : CGST','LR',0,L);
		$pdf->SetFont('Arial','',9);
		$pdf->Cell(23,5,number_format((float)$cgst, 2),0,1,R);

		$pdf->Cell(120,5,'',0,0,L);
		$pdf->SetFont('Arial','I',9);
		$pdf->Cell(47,5,'Add   : SGST','RL',0,L);
		$pdf->SetFont('Arial','',9);
		$pdf->Cell(23,5,number_format((float)$sgst, 2),0,1,R);
	}else{
		$pdf->SetFont('Arial','I',9);
		$pdf->Cell(120,5,'',0,0,L);
		$pdf->Cell(47,5,'Add   : IGST','LR',0,L);
		$pdf->SetFont('Arial','',9);
		$pdf->Cell(23,5,number_format((float)$igst, 2),0,1,R);
	}

	if(($addons_array['roundoff'] ?? '')!='' && (float)($addons_array['roundoff'] ?? 0) != 0)
	{
		if((float)$addons_array['roundoff'] < 0){
			$roundoff_temp = $addons_array['roundoff'] * -1;
			$pdf->Cell(120,5,'',0,0,L);
			$pdf->SetFont('Arial','I',9);
			$pdf->Cell(47,5,'Less : Rounded Off (-)','LR',0,L);
			$pdf->SetFont('Arial','',9);
			$pdf->Cell(23,5,number_format((float)$roundoff_temp, 2),0,1,R);
		}else{
			$pdf->Cell(120,5,'',0,0,L);
			$pdf->SetFont('Arial','I',9);
			$pdf->Cell(47,5,'Add : Rounded Off (+)','LR',0,L);
			$pdf->SetFont('Arial','',9);
			$pdf->Cell(23,5,number_format((float)$addons_array['roundoff'], 2),0,1,R);
		}
	}

	$pdf->Cell(120,3,'','R',0,C);
	$pdf->Cell(47,3,'','R',0,C);
	$pdf->Cell(23,3,'','B',1,C);
	//End Addons

	$pdf->SetFont('Arial','B',9);

	$pdf->Cell(80,7,'',0,0,R);
	$pdf->Cell(40,7,'','',0,C);
	$pdf->Cell(47,7,'GRAND TOTAL','LTB',0,L);

	if($state_flag == '0'){
		$total_amount = (float)$GLOBALS["gross_total"] + (float)($addons_array['pf']['value'] ?? 0) + (float)($addons_array['freight']['value'] ?? 0) + (float)$sgst + (float)$cgst + (float)($addons_array['roundoff'] ?? 0);
	}else{
		$total_amount = (float)$GLOBALS["gross_total"] + (float)($addons_array['pf']['value'] ?? 0) + (float)($addons_array['freight']['value'] ?? 0) + (float)$igst + (float)($addons_array['roundoff'] ?? 0);
	}

	$pdf->Cell(23,7,number_format((float)$total_amount, 2),'LB',1,R);

	
	

	//--------------------------------------------------- HSN Wise Summary --------------------------------------------------

	if($show_hsn == '1')
	{
		$len = sizeof($tax_details['hsn']);

		$tmp_y = $pdf->getY();
		$hsn_limit = 297 - 13 - ($len*5) - 40;
		if($tmp_y > $hsn_limit){
			$GLOBALS["pages"]++;
			$pdf->AddPage();
		}
		
		$pdf->SetY($tmp_yy);

		if($state_flag == '0'){
			$pdf->SetFont('Arial','B',7);
			//$pdf->Cell(190,3,'',0,1,C);
			$pdf->Cell(35,5,'HSN/SAC','LTBR',0,C);
			$pdf->Cell(15,5,'Tax Rate','TBR',0,C);
			$pdf->Cell(15,5,'Taxable Amt.','TBR',0,C);
			$pdf->Cell(15,5,'CGST','TBR',0,C);
			$pdf->Cell(15,5,'SGST','TBR',0,C);
			$pdf->Cell(20,5,'Total Tax','TBR',1,C);

			$tot_taxable = 0; $tot_cgst = 0; $tot_sgst = 0; $tot_total = 0;

			for($i=0;$i<$len;$i++){
				$pdf->SetFont('Arial','',7);
				$pdf->Cell(35,5,$tax_details['hsn'][$i],'LR',0,C);
				$temp = $tax_details['rate'][$i].'%';
				$pdf->Cell(15,5,$temp,'R',0,C);
				$pdf->Cell(15,5,number_format((float)$tax_details['taxable'][$i], 2),'LR',0,C);
				$pdf->Cell(15,5,number_format((float)$tax_details['cgst'][$i], 2),'R',0,C);
				$pdf->Cell(15,5,number_format((float)$tax_details['sgst'][$i], 2),'R',0,C);
				$pdf->Cell(20,5,number_format((float)$tax_details['total'][$i], 2),'R',1,C);

				$tot_taxable += $tax_details['taxable'][$i];
				$tot_cgst += $tax_details['cgst'][$i];
				$tot_sgst += $tax_details['sgst'][$i];
				$tot_total += $tax_details['total'][$i];
			}

			$pdf->SetFont('Arial','B',7);
			$pdf->Cell(35,5,'Totals','TBR',0,R);
			$pdf->Cell(15,5,'','TBR',0,C);
			$pdf->Cell(15,5,number_format((float)$tot_taxable, 2),'LTBR',0,C);
			$pdf->Cell(15,5,number_format((float)$tot_cgst, 2),'TBR',0,C);
			$pdf->Cell(15,5,number_format((float)$tot_sgst, 2),'TBR',0,C);
			$pdf->Cell(20,5,number_format((float)$tot_total, 2),'TBR',1,C);
		}
		else{
			$pdf->SetFont('Arial','B',7);
			//$pdf->Cell(190,3,'',0,1,C);
			$pdf->Cell(35,5,'HSN/SAC','LTBR',0,C);
			$pdf->Cell(15,5,'Tax Rate','TBR',0,C);
			$pdf->Cell(15,5,'Taxable Amt.','TBR',0,C);
			$pdf->Cell(15,5,'IGST','TBR',0,C);
			$pdf->Cell(20,5,'Total Tax','TBR',1,C);

			$tot_taxable = 0; $tot_igst = 0; $tot_total = 0;

			for($i=0;$i<$len;$i++){
				$pdf->SetFont('Arial','',7);
				$pdf->Cell(35,5,$tax_details['hsn'][$i],'LR',0,C);
				$temp = $tax_details['rate'][$i].'%';
				$pdf->Cell(15,5,$temp,'R',0,C);
				$pdf->Cell(15,5,number_format((float)$tax_details['taxable'][$i], 2),'LR',0,C);
				$pdf->Cell(15,5,number_format((float)$tax_details['igst'][$i], 2),'R',0,C);
				$pdf->Cell(20,5,number_format((float)$tax_details['total'][$i], 2),'R',1,C);

				$tot_taxable += $tax_details['taxable'][$i];
				$tot_igst += $tax_details['igst'][$i];
				$tot_total += $tax_details['total'][$i];
			}

			$pdf->SetFont('Arial','B',7);
			$pdf->Cell(35,5,'Totals','LTBR',0,C);
			$pdf->Cell(15,5,'','TBR',0,C);
			$pdf->Cell(15,5,number_format((float)$tot_taxable, 2),'LTBR',0,C);
			$pdf->Cell(15,5,number_format((float)$tot_igst, 2),'TBR',0,C);
			$pdf->Cell(20,5,number_format((float)$tot_total, 2),'TBR',1,C);
		}

		$tax_amount_words = "Tax Amount (in words) : ".convertToIndianCurrency($tot_total);
		$pdf->Cell(190,8,$tax_amount_words,'',1,L);
	}
	
	$pdf->SetFont('Arial','B',7);
	$total_amount_words = "Amount Chargeable (in words) : ".convertToIndianCurrency($total_amount);
	$pdf->Cell(190,10,$total_amount_words,0,1,L);
	
	

	
	

	$tmp_y = $pdf->getY();
	$tr_flag = 0;
	//$pdf->Cell(190,20,$tmp_y,'TBLR',1,L);
	$tmp_y = $pdf->getY();
	$terms_limit = 297 - 77	;
	// $pdf->Cell(90,2,$tmp_y,'TBLR',0,L);
	// $pdf->Cell(90,2,$terms_limit,'TBLR',1,L);
	if($tmp_y > $terms_limit){	
		$GLOBALS["pages"]++;
		$pdf->AddPage();
		$tr_flag=1;
		$pdf->setY(220);
		//$pdf->Cell(190,8,$tmp_y,'TBLR',1,L);
		// $pdf->setY($terms_limit);
	}else{
		$pdf->setY(220);
		//$pdf->Cell(190,8,$tmp_y,'TR',1,L);
	}

	$pdf->SetFont('Arial','',9);
	$pdf->Cell(190,6,'BANK NAME : AXIS BANK, BRANCH : DALHOUSIE, A/C NO. : 918020014434303, IFSC : UTIB0000153','T',2,C);
	

	$y = $pdf->getY();
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(95,1,'','TR',0,L);
	$pdf->Cell(95,1,'','T',1,L);
	$pdf->Cell(95,5,'Customer\'s Signature:','',0,L);
	$pdf->Image("../media/pdf/qr.jpg",90,$y,30,30);
	$pdf->Cell(95,5,'for M.M. LUCKY ENTERPRISE',0,1,R);
	$pdf->Image("../media/company-logos/company_stamp.png",170,235,20,20);
	$pdf->Cell(95,12,'','',0,L);
	$pdf->Cell(95,12,'','',1,R);
	$pdf->Cell(95,4,'','',1,L);
	$pdf->Cell(95,4,'','',0,L);
	$pdf->Cell(95,4,'Authorised Signatory',0,1,R);

	if($tr_flag == 1){
		$pdf->Cell(95,3,'','BR',0,L);
		$pdf->Cell(95,3,'','B',1,L);
	}else{
		// $pdf->Cell(95,3,'','R',0,L);
		// $pdf->Cell(95,3,'','',1,L);
	}
}


//------------------------------------------------- Terms & Conditions Block ------------------------------------------------------

$name = "Invoice_EH-".substr($GLOBALS["si_no"],6,4)."_".str_replace('-','',$GLOBALS["dt"]).".pdf";

if($pdf_type == 'print'){
	$pdf->AutoPrint();
	$pdf->output('I',$name);
}else if($pdf_type == 'download'){
	$pdf->output('D',$name);
}else if($pdf_type == 'ledger'){
	$pdf->output('I',$name);
}

?>

