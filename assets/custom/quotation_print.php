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
		$this->Cell(70,7,'Quotation',0,0,C);
		$this->SetFont('Arial','',8);
		$this->Cell(60,7,$GLOBALS['label'],0,1,R);

		$this->SetFont('Arial','B',18);
		$this->Cell(190,8,'M.M. Lucky Enterprise',0,2,C);
		$this->SetFont('Arial','',9);
		$this->Cell(190,4,'26, Strand Road, Ground Floor,',0,2,C);
		$this->Cell(190,4,'Kolkata - 700 001, West Bengal, India',0,2,C);
		//$this->Cell(190,4,'',0,1,C);
		$this->Cell(190,4,'Email:mmleind@gmail.com',0,4,C);
		//$this->Cell(190,4,'',0,1,C);
		$this->Cell(190,4,'Phone:+91 6289778473 ',0,2,C);
		
		$this->SetFont('Arial','B',10);
		$this->Cell(190,4,'GST : 19ALCPM0139R1ZO',B,1,C);

	    $this->Image("../media/company-logos/logo.jpg",10,15,50,20);
	    // $this->Image("../media/pdf/contact.jpg",10,40,3,3);
	    $this->Image("../media/pdf/email.jpg",80,36,3,3);
	    $this->Image("../media/pdf/whatsapp.jpg",83,40,3,3);

		
		$this->Ln(2);

		$y = $this->getY();

		$this->SetFont('Arial','B',9);
		$this->Cell(90,5,'Customer Details :            ','R',2,L);
		$this->SetFont('Arial','',9);
		$this->CellFitScale(90,5,$GLOBALS["client"],'R',2,L);
		$tmp = "MOBILE : ".$GLOBALS["mobile"];
		$this->CellFitScale(90,5,$tmp,'R',2,L);
		$this->CellFitScale(90,5,$GLOBALS["add1"],'R',2,L);
		$tmp = $GLOBALS["add2"];
		$this->CellFitScale(90,5,$tmp,'R',2,L);
		$tmp = $GLOBALS["city"].' - '.$GLOBALS["pincode"].', '.$GLOBALS["state"].', '.$GLOBALS["country"];
		$this->CellFitScale(90,5,$tmp,'R',2,L);
		$temp = 'GSTIN / UIN : '.$GLOBALS["gstin"];
		$this->Cell(90,5,$temp,'R',1,L);
		$this->Cell(90,3,'','R',1,L);

		$this->setXY('102',$y);
		$this->SetFont('Arial','',9);
		$this->Cell(25,5,'Quotation No.',0,0,L);
		$temp = ':   '.$GLOBALS["q_no"];
		$this->Cell(75,5,$temp,0,1,L);
		$this->setX('102');
		$this->Cell(25,5,'Dated',0,0,L);
		$temp = ':   '.$GLOBALS["dt"];
		$this->Cell(75,5,$temp,0,1,L);
		$this->setX('102');
		$this->CellFitScale(25,5,'Inquiry No.',0,0,L);
		$temp = ':   '.$GLOBALS["top1"];
		$this->Cell(75,5,$temp,0,1,L);
		$this->setX('102');
		$this->Cell(25,5,'Inquiry Date',0,0,L);
		$temp = ':   '.$GLOBALS["top2"];
		$this->Cell(75,5,$temp,0,1,L);
		$this->setX('102');
		$this->Cell(25,5,'Contact Person',0,0,L);
		$temp = ':   '.$GLOBALS["top3"];
		$this->Cell(75,5,$temp,0,1,L);
		$this->setX('102');
		$this->Cell(25,5,'Contact Number',0,0,L);
		$temp = ':   '.$GLOBALS["top4"];
		$this->Cell(75,5,$temp,0,1,L);
		$this->setX('102');
		$this->Cell(100,5,$GLOBALS["vendor"],0,2,L);
		$y = $this->getY();
		$this->setXY('10',$y);
		$this->Cell(190,3,'','B',1,L);
	}

	// Page footer
	function Footer()
	{

		$this->Image("../media/pdf/quot_bottom.jpg",10,263,190,24);
		$this->Line(10,262,200,262);
	    // Position at 1.5 cm from bottom
	    $this->SetY(-15);
	    // Arial italic 8
	    $this->SetFont('Arial','I',8);
	    // Page number
	    $this->Cell(0,20,'Page '.$this->PageNo().'/{nb}',0,0,'C');
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

$q_no = $_REQUEST['id'] ?? '';
$pdf_type = $_REQUEST['type'] ?? '';

$sql = "SELECT * FROM quotation WHERE `quotation_no` = '$q_no'";
$query = $db->query($sql);
if (!$query || !($row = $query->fetch_assoc())) {
	die('Record not found');
}

$display_totals = $row['display_totals'] ?? '';
$display_hsn = $row['display_hsn'] ?? '';
$GLOBALS["mobile"] = $row['mobile'] ?? '';

$client = $row['client'] ?? '';
$top = json_decode($row['quotation_top'] ?? '', true);
if (!is_array($top)) { $top = []; }
$terms = json_decode($row['terms'] ?? '', true);
if (!is_array($terms)) { $terms = []; }
$items = json_decode($row['items'] ?? '', true);
if (!is_array($items)) { $items = []; }

$sql_temp = "SELECT * FROM clients WHERE name = '$client'";
$query_temp = $db->query($sql_temp);
$row_temp = $query_temp ? $query_temp->fetch_assoc() : null;
$contacts = [];
if ($row_temp) {
	$contacts = json_decode($row_temp['contacts'] ?? '', true);
}
if (!is_array($contacts)) { $contacts = []; }


if($row_temp != null) {
	$address = json_decode($row['address'] ?? '', true);
} else {
	$address = json_decode($row['address'] ?? '', true);
}
if (!is_array($address)) { $address = []; }


$GLOBALS["gross_total"] = '0';
$GLOBALS["q_no"] = $q_no;
$GLOBALS["dt"] = !empty($row['quotation_date']) ? date('d-m-Y', strtotime($row['quotation_date'])) : '';

if($row_temp && ($row_temp['print_name'] ?? '') != '')
	$GLOBALS['client'] = $row_temp['print_name'];
else
	$GLOBALS['client'] = $row['client'] ?? '';
	
if($row_temp && ($row_temp['vendor_code'] ?? null)!=null)
	{
		$GLOBALS["vendor"] = "Vendor Code       :  ".$row_temp['vendor_code'];
	}

$GLOBALS['add1'] 	= $address["address_1"] ?? '';
$GLOBALS['add2'] 	= $address["address_2"] ?? '';
$GLOBALS['city'] 	= $address["city"] ?? '';
$GLOBALS['pincode'] = $address["pincode"] ?? '';

if ($row_temp == null) {
	$GLOBALS['state'] 	= $address["state"] ?? '';
	$GLOBALS['country'] = $address["country"] ?? '';
	$GLOBALS['gstin'] 	= $address["gstin"] ?? '';
} else {
	$GLOBALS['state'] = $row_temp["state"] ?? '';
	$GLOBALS['country'] = $row_temp["country"] ?? '';
	$GLOBALS['gstin'] = $row_temp["gstin"] ?? '';
}

$GLOBALS['top1'] = $top["cl_enquiry_no"][0] ?? '';
$GLOBALS['top2'] = $top["enquiry_date"][0] ?? '';

$GLOBALS['terms1'] = $terms["prices"] ?? '';
$GLOBALS['terms2'] = $terms["pf"] ?? '';
$GLOBALS['terms3'] = $terms["freight"] ?? '';
$GLOBALS['terms4'] = $terms["delivery"] ?? '';
$GLOBALS['terms5'] = $terms["payment"] ?? '';
$GLOBALS['terms6'] = $terms["validity"] ?? '';
$GLOBALS['terms7'] = $terms["remarks"] ?? '';

$flag = 1;

if ($row_temp == null) {
	if(($address["state"] ?? '') == 'WEST BENGAL'){
		$flag = 0;
	}
} else {
	if(($row_temp["state"] ?? '') == 'WEST BENGAL'){
		$flag = 0;
	}
}

$pdf = new PDF_AutoPrint();
$pdf->SetAutoPageBreak(true, 35);
$pdf->setMargins(10, 10);
$title = "Quotation";
$pdf->SetTitle($title);

$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->setX('10');

// ------------------------------------------------------ Table Header ---------------------------------------------------------------
if($flag == 0)
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

	if($flag == 0)
	{
		$tax = (float)($items['tax'][$i] ?? 0)/2;
		$cgst = (float)($items['cgst'][$i] ?? 0);
		$sgst = (float)($items['sgst'][$i] ?? 0);
		$pr = $items['product'][$i] ?? '';
		$make = $items['group'][$i] ?? '';

		$line_total = (float)($items['quantity'][$i] ?? 0)*(float)($items['price'][$i] ?? 0)*(100-(float)($items['discount'][$i] ?? 0))/100;
		$GLOBALS["gross_total"] += $line_total;

		$sql_make = "SELECT * FROM product WHERE name = '$pr'";
		$query_make = $db->query($sql_make);
		$row_make = $query_make ? $query_make->fetch_assoc() : null;
		$pr_group = strtoupper((string)($row_make['group'] ?? ''));

		$temp = $items['product'][$i];
		$product = $temp;
		// $product = dotcom_wordwrap($temp,40);
		$co = 1;

		if($make == '1')
			$temp = $items['desc'][$i].', Make : '.$pr_group;
		else
			$temp = $items['desc'][$i];

		$desc = dotcom_wordwrap($temp,50);
		$co_2 = count($desc);

		$description_array = explode('|', (string)($items['long_desc'][$i] ?? ''));
		$len = sizeof($description_array);

		$limit = $co * 5 + $co_2 * 5 + $len * 3;

		$tmp_y = $pdf->getY();
		$product_limit = 297 - 35 - $limit;
		if($tmp_y > $product_limit){
			$pdf->AddPage();
			if($flag == 0)
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
		$pdf->CellFitScale(63,5,$product,'R',0,L);
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

		// if($co > 1){
		// 	for( $z=1 ; $z<$co ; $z++){
		// 		$pdf->Cell(7,5,'','R',0,C);
		// 		$pdf->SetFont('Arial','B',7);
		// 		$pdf->Cell(63,5,$product[$z],'R',0,L);
		// 		$pdf->SetFont('Arial','',7);
		// 		$pdf->Cell(10,5,'','R',0,C);
		// 		$pdf->Cell(10,5,'','R',0,C);
		// 		$pdf->Cell(10,5,'','R',0,C);
		// 		$pdf->Cell(17,5,'','R',0,R);
		// 		$pdf->Cell(10,5,'','R',0,C);
		// 		$pdf->Cell(8,5,'','R',0,C);
		// 		$pdf->Cell(12,5,'','R',0,C);
		// 		$pdf->Cell(8,5,'','R',0,C);
		// 		$pdf->Cell(12,5,'','R',0,C);
		// 		$pdf->Cell(23,5,'','',1,R);
				
		// 	}
		// }

		// Printing SKU & Make
		for( $z=0 ; $z<$co_2 ; $z++){
			$pdf->Cell(7,5,'','R',0,C);
			$pdf->SetFont('Arial','I',7);
			$pdf->Cell(63,5,$desc[$z],'R',0,L);
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
		for($k=0;$k<$len;$k++){
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
			$pdf->Cell(8,3,'','R',0,C);
			$pdf->Cell(12,3,'','R',0,C);
			$pdf->Cell(8,3,'','R',0,C);
			$pdf->Cell(12,3,'','R',0,C);
			$pdf->Cell(23,3,'','',1,R);
		}

	}else{
		$tax = (float)($items['tax'][$i] ?? 0);
		$igst = (float)($items['igst'][$i] ?? 0);
		$pr = $items['product'][$i] ?? '';
		$make = $items['group'][$i] ?? '';

		$line_total = (float)($items['quantity'][$i] ?? 0)*(float)($items['price'][$i] ?? 0)*(100-(float)($items['discount'][$i] ?? 0))/100;
		$GLOBALS["gross_total"] += $line_total;

		$sql_make = "SELECT * FROM product WHERE name = '$pr'";
		$query_make = $db->query($sql_make);
		$row_make = $query_make ? $query_make->fetch_assoc() : null;
		$pr_group = strtoupper((string)($row_make['group'] ?? ''));

		$temp = $items['product'][$i];
		$product = $temp;
		// $product = dotcom_wordwrap($temp,40);
		$co = 1;

		if($make == '1'){
			$temp = $items['desc'][$i].', Make : '.$pr_group;
			// $temp = 'Make : '.$pr_group;
		}
		else
			$temp = $items['desc'][$i];

		$desc = dotcom_wordwrap($temp,50);
		$co_2 = count($desc);

		$description_array = explode('|', (string)($items['long_desc'][$i] ?? ''));
		$len = sizeof($description_array);

		$limit = $co * 5 + $co_2 * 5 + $len * 3;

		$tmp_y = $pdf->getY();
		$product_limit = 297 - 35 - $limit;
		if($tmp_y > $product_limit){
			$pdf->AddPage();
			if($flag == 0)
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
		$pdf->CellFitScale(63,5,$product,'R',0,L);
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

		// if($co > 1){
		// 	for( $z=1 ; $z<$co ; $z++){
		// 		$pdf->Cell(7,5,'','R',0,C);
		// 		$pdf->SetFont('Arial','B',7);
		// 		$pdf->Cell(63,5,$product[$z],'R',0,L);
		// 		$pdf->SetFont('Arial','',7);
		// 		$pdf->Cell(10,5,'','R',0,C);
		// 		$pdf->Cell(10,5,'','R',0,C);
		// 		$pdf->Cell(10,5,'','R',0,C);
		// 		$pdf->Cell(17,5,'','R',0,R);
		// 		$pdf->Cell(10,5,'','R',0,C);
		// 		$pdf->Cell(20,5,'','R',0,C);
		// 		$pdf->Cell(20,5,'','R',0,C);
		// 		$pdf->Cell(23,5,'','',1,R);
				
		// 	}
		// }

		// Printing SKU & Make
		for( $z=0 ; $z<$co_2 ; $z++){
			$pdf->Cell(7,5,'','R',0,C);
			$pdf->SetFont('Arial','I',7);
			$pdf->Cell(63,5,$desc[$z],'R',0,L);
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

	$grand_total_qty+=(float)($items['quantity'][$i] ?? 0);

	$hsn = $items['hsn'][$i] ?? '';
	$pos = '-1';
	$len = sizeof($tax_details['hsn']);
	for($j=0;$j<$len;$j++){
		if($tax_details['hsn'][$j] == $hsn){
			$pos = $j;
			break;
		}
	}

	$item_cgst = (float)($items['cgst'][$i] ?? 0);
	$item_sgst = (float)($items['sgst'][$i] ?? 0);
	$item_igst = (float)($items['igst'][$i] ?? 0);
	if($pos != '-1'){
		$tax_details['taxable'][$pos] += $line_total;
		$tax_details['cgst'][$pos] += $item_cgst;
		$tax_details['sgst'][$pos] += $item_sgst;
		$tax_details['igst'][$pos] += $item_igst;
		$tax_details['total'][$pos] += $item_cgst + $item_sgst + $item_igst;

	}else{
		$tax_details['hsn'][] = $items['hsn'][$i] ?? '';
		$tax_details['rate'][] = $items['tax'][$i] ?? 0;
		$tax_details['taxable'][] = $line_total;
		$tax_details['cgst'][] = $item_cgst;
		$tax_details['sgst'][] = $item_sgst;
		$tax_details['igst'][] = $item_igst;
		$tax_details['total'][] = $item_cgst + $item_sgst + $item_igst;
	}

}



if($display_totals == 1)
{
	//Addons
	$pdf->Cell(167,3,'','TR',0,C);
	$pdf->Cell(23,3,'','T',1,C);

	// $t_tax = $items['tax'][0];
	// $t2_tax = $t_tax/2;

	$addons_array = json_decode($row['addons'] ?? '', true);
	if (!is_array($addons_array)) { $addons_array = []; }

	$pdf->Cell(95,5,'',0,0,L);
	$pdf->SetFont('Arial','I',9);
	$pdf->Cell(72,5,'Gross Total','R',0,L);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(23,5,number_format((float)$GLOBALS["gross_total"], 2),0,1,R);

	if($addons_array['pf']['value']!='' && $addons_array['pf']['value'] > 0){
		$pdf->Cell(95,5,'',0,0,L);
		$pdf->SetFont('Arial','I',9);
		$pdf->Cell(72,5,'Add   : Packaging & Forwarding','R',0,L);
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

		$pf_value = (float)($addons_array['pf']['value'] ?? 0);
		$pf_cgst = (float)($addons_array['pf']['cgst'] ?? 0);
		$pf_sgst = (float)($addons_array['pf']['sgst'] ?? 0);
		$pf_igst = (float)($addons_array['pf']['igst'] ?? 0);
		if($pos != '-1'){
			$tax_details['taxable'][$pos] += $pf_value;
			$tax_details['cgst'][$pos] += $pf_cgst;
			$tax_details['sgst'][$pos] += $pf_sgst;
			$tax_details['igst'][$pos] += $pf_igst;
			$tax_details['total'][$pos] += $pf_cgst + $pf_sgst + $pf_igst;

		}else{
			$tax_details['hsn'][] = $hsn;
			$tax_details['rate'][] = '18';
			$tax_details['taxable'][] = $pf_value;
			$tax_details['cgst'][] = $pf_cgst;
			$tax_details['sgst'][] = $pf_sgst;
			$tax_details['igst'][] = $pf_igst;
			$tax_details['total'][] = $pf_cgst + $pf_sgst + $pf_igst;
		}
	}

	if($addons_array['freight']['value']!='' && $addons_array['freight']['value'] > 0){
		$pdf->Cell(95,5,'',0,0,L);
		$pdf->SetFont('Arial','I',9);
		$pdf->Cell(72,5,'Add   : Freight','R',0,L);
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

		$fr_value = (float)($addons_array['freight']['value'] ?? 0);
		$fr_cgst = (float)($addons_array['freight']['cgst'] ?? 0);
		$fr_sgst = (float)($addons_array['freight']['sgst'] ?? 0);
		$fr_igst = (float)($addons_array['freight']['igst'] ?? 0);
		if($pos != '-1'){
			$tax_details['taxable'][$pos] += $fr_value;
			$tax_details['cgst'][$pos] += $fr_cgst;
			$tax_details['sgst'][$pos] += $fr_sgst;
			$tax_details['igst'][$pos] += $fr_igst;
			$tax_details['total'][$pos] += $fr_cgst + $fr_sgst + $fr_igst;

		}else{
			$tax_details['hsn'][] = $hsn;
			$tax_details['rate'][] = '18';
			$tax_details['taxable'][] = $fr_value;
			$tax_details['cgst'][] = $fr_cgst;
			$tax_details['sgst'][] = $fr_sgst;
			$tax_details['igst'][] = $fr_igst;
			$tax_details['total'][] = $fr_cgst + $fr_sgst + $fr_igst;
		}
	}

	$sgst = 0;
	$cgst = 0;
	$igst = 0;

	$len = sizeof($tax_details['hsn']);
	for($j=0;$j<$len;$j++){
		$sgst += (float)($tax_details['sgst'][$j] ?? 0);
		$cgst += (float)($tax_details['cgst'][$j] ?? 0);
		$igst += (float)($tax_details['igst'][$j] ?? 0);
	}

	if($flag == '0'){
		$pdf->SetFont('Arial','I',9);
		$pdf->Cell(95,5,'',0,0,L);
		$pdf->Cell(72,5,'Add   : CGST','R',0,L);
		$pdf->SetFont('Arial','',9);
		$pdf->Cell(23,5,number_format((float)$cgst, 2),0,1,R);

		$pdf->Cell(95,5,'',0,0,L);
		$pdf->SetFont('Arial','I',9);
		$pdf->Cell(72,5,'Add   : SGST','R',0,L);
		$pdf->SetFont('Arial','',9);
		$pdf->Cell(23,5,number_format((float)$sgst, 2),0,1,R);
	}else{
		$pdf->SetFont('Arial','I',9);
		$pdf->Cell(95,5,'',0,0,L);
		$pdf->Cell(72,5,'Add   : IGST','R',0,L);
		$pdf->SetFont('Arial','',9);
		$pdf->Cell(23,5,number_format((float)$igst, 2),0,1,R);
	}

	if($addons_array['roundoff']!='' && $addons_array['roundoff'] != 0)
	{
		if($addons_array['roundoff'] < 0){
			$roundoff_temp = $addons_array['roundoff'] * -1;
			$pdf->Cell(95,5,'',0,0,L);
			$pdf->SetFont('Arial','I',9);
			$pdf->Cell(72,5,'Less : Rounded Off (-)','R',0,L);
			$pdf->SetFont('Arial','',9);
			$pdf->Cell(23,5,number_format((float)$roundoff_temp, 2),0,1,R);
		}else{
			$pdf->Cell(95,5,'',0,0,L);
			$pdf->SetFont('Arial','I',9);
			$pdf->Cell(72,5,'Add : Rounded Off (+)','R',0,L);
			$pdf->SetFont('Arial','',9);
			$pdf->Cell(23,5,number_format((float)$addons_array['roundoff'], 2),0,1,R);
		}
	}

	$pdf->Cell(167,3,'','BR',0,C);
	$pdf->Cell(23,3,'','B',1,C);
	//End Addons

	$pdf->SetFont('Arial','B',9);

	$pdf->Cell(80,7,'',0,0,R);
	$pdf->Cell(10,7,$grand_total_qty,'B',0,C);
	$pdf->Cell(77,7,'GRAND TOTAL',0,0,R);

	$addon_pf_value = (float)($addons_array['pf']['value'] ?? 0);
	$addon_fr_value = (float)($addons_array['freight']['value'] ?? 0);
	$addon_roundoff = (float)($addons_array['roundoff'] ?? 0);
	if($flag == '0'){
		$total_amount = (float)$GLOBALS["gross_total"] + $addon_pf_value + $addon_fr_value + $sgst + $cgst + $addon_roundoff;
	}else{
		$total_amount = (float)$GLOBALS["gross_total"] + $addon_pf_value + $addon_fr_value + $igst + $addon_roundoff;
	}

	$pdf->Cell(23,7,number_format((float)$total_amount, 2),'LB',1,R);

	$pdf->SetFont('Arial','',9);

	$pdf->Cell(190,10,convertToIndianCurrency($total_amount),0,1,L);

	//--------------------------------------------------- HSN Wise Summary -----------------------------------------------------------

	if($display_hsn == 1)
	{
		$len = sizeof($tax_details['hsn']);

		$tmp_y = $pdf->getY();
		$hsn_limit = 297 - 13 - ($len*5) - 40;
		if($tmp_y > $hsn_limit)
			$pdf->AddPage();

		if($flag == '0'){
			$pdf->SetFont('Arial','B',7);
			$pdf->Cell(190,3,'',0,1,C);
			$pdf->Cell(15,5,'HSN/SAC','B',0,C);
			$pdf->Cell(15,5,'Tax Rate','B',0,C);
			$pdf->Cell(15,5,'Taxable Amt.','B',0,C);
			$pdf->Cell(15,5,'CGST','B',0,C);
			$pdf->Cell(15,5,'SGST','B',0,C);
			$pdf->Cell(20,5,'Total Tax','B',1,C);

			$tot_taxable = 0; $tot_cgst = 0; $tot_sgst = 0; $tot_total = 0;

			for($i=0;$i<$len;$i++){
				$pdf->SetFont('Arial','',7);
				$pdf->Cell(15,5,$tax_details['hsn'][$i],'',0,C);
				$temp = $tax_details['rate'][$i].'%';
				$pdf->Cell(15,5,$temp,'',0,C);
				$pdf->Cell(15,5,number_format((float)$tax_details['taxable'][$i], 2),'',0,C);
				$pdf->Cell(15,5,number_format((float)$tax_details['cgst'][$i], 2),'',0,C);
				$pdf->Cell(15,5,number_format((float)$tax_details['sgst'][$i], 2),'',0,C);
				$pdf->Cell(20,5,number_format((float)$tax_details['total'][$i], 2),'',1,C);

				$tot_taxable += $tax_details['taxable'][$i];
				$tot_cgst += $tax_details['cgst'][$i];
				$tot_sgst += $tax_details['sgst'][$i];
				$tot_total += $tax_details['total'][$i];
			}

			$pdf->SetFont('Arial','B',7);
			$pdf->Cell(15,5,'Totals','TB',0,C);
			$pdf->Cell(15,5,'','TB',0,C);
			$pdf->Cell(15,5,number_format((float)$tot_taxable, 2),'TB',0,C);
			$pdf->Cell(15,5,number_format((float)$tot_cgst, 2),'TB',0,C);
			$pdf->Cell(15,5,number_format((float)$tot_sgst, 2),'TB',0,C);
			$pdf->Cell(20,5,number_format((float)$tot_total, 2),'TB',1,C);
		}
		else{
			$pdf->SetFont('Arial','B',7);
			$pdf->Cell(190,3,'',0,1,C);
			$pdf->Cell(15,5,'HSN/SAC','B',0,C);
			$pdf->Cell(15,5,'Tax Rate','B',0,C);
			$pdf->Cell(15,5,'Taxable Amt.','B',0,C);
			$pdf->Cell(15,5,'IGST','B',0,C);
			$pdf->Cell(20,5,'Total Tax','B',1,C);

			$tot_taxable = 0; $tot_igst = 0; $tot_total = 0;

			for($i=0;$i<$len;$i++){
				$pdf->SetFont('Arial','',7);
				$pdf->Cell(15,5,$tax_details['hsn'][$i],'',0,C);
				$temp = $tax_details['rate'][$i].'%';
				$pdf->Cell(15,5,$temp,'',0,C);
				$pdf->Cell(15,5,number_format((float)$tax_details['taxable'][$i], 2),'',0,C);
				$pdf->Cell(15,5,number_format((float)$tax_details['igst'][$i], 2),'',0,C);
				$pdf->Cell(20,5,number_format((float)$tax_details['total'][$i], 2),'',1,C);

				$tot_taxable += $tax_details['taxable'][$i];
				$tot_igst += $tax_details['igst'][$i];
				$tot_total += $tax_details['total'][$i];
			}

			$pdf->SetFont('Arial','B',7);
			$pdf->Cell(15,5,'Totals','TB',0,C);
			$pdf->Cell(15,5,'','TB',0,C);
			$pdf->Cell(15,5,number_format((float)$tot_taxable, 2),'TB',0,C);
			$pdf->Cell(15,5,number_format((float)$tot_igst, 2),'TB',0,C);
			$pdf->Cell(20,5,number_format((float)$tot_total, 2),'TB',1,C);
		}
	}
}
else{
	//Addons
	$pdf->Cell(167,3,'','T',0,C);
	$pdf->Cell(23,3,'','T',1,C);
}


//------------------------------------------------- Terms & Conditions Block ------------------------------------------------------
$pdf->Ln();
$terms_limit_add=0;
for($i=1;$i<=7;$i++){
	$temp = "terms".$i;
	$terms = dotcom_wordwrap($GLOBALS[$temp],70);
	$co=count($terms);
	if($co > 1){
		$terms_limit_add += $co-1;
	}
}

$tr_flag=0;
$tmp_y = $pdf->getY();
$terms_limit = 297 - 35 - 57 - ($terms_limit_add*4);
if($tmp_y > $terms_limit){
	$pdf->AddPage();
	$tr_flag=1;
}else{
	$pdf->setY($terms_limit);
}

$pdf->SetFont('Arial','',9);

$pdf->Cell(190,8,'BANK NAME : AXIS BANK, BRANCH : DALHOUSIE, A/C NO. : 918020014434303, IFSC : UTIB000153','T',2,C);

$y = $pdf->getY();
$pdf->SetFont('Arial','B',9);
$pdf->Cell(120,3,'','TR',0,L);
$pdf->Cell(70,3,'','T',1,L);
$pdf->Cell(120,5,'TERMS & CONDITIONS:','R',0,L);
$pdf->Cell(70,5,'for M M LUCKY Enterprise',0,1,R);

$terms = dotcom_wordwrap($GLOBALS["terms1"],70);
$co=count($terms);

if($co>=1){
	for( $z=0 ; $z<$co ; $z++){
		$pdf->SetFont('Arial','B',8);
		if($z == 0)
			$pdf->Cell(20,4,'Prices',0,0,L);
		else
			$pdf->Cell(20,4,'',0,0,L);
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(3,4,':   ',0,0,L);
		$pdf->CellFitScale(97,4,$terms[$z],'R',0,L);
		$pdf->SetFont('Arial','B',9);
		$pdf->Cell(70,4,'',0,1,C);
	}
}

$terms = dotcom_wordwrap($GLOBALS["terms2"],70);
$co=count($terms);

if($co>=1){
	for( $z=0 ; $z<$co ; $z++){
		$pdf->SetFont('Arial','B',8);
		if($z == 0)
			$pdf->Cell(20,4,'P & F',0,0,L);
		else
			$pdf->Cell(20,4,'',0,0,L);
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(3,4,':   ',0,0,L);
		$pdf->CellFitScale(97,4,$terms[$z],'R',0,L);
		$pdf->SetFont('Arial','B',9);
		$pdf->Cell(70,4,'',0,1,C);	
	}
}

$terms = dotcom_wordwrap($GLOBALS["terms3"],70);
$co=count($terms);

if($co>=1){
	for( $z=0 ; $z<$co ; $z++){
		$pdf->SetFont('Arial','B',8);
		if($z == 0)
			$pdf->Cell(20,4,'Freight',0,0,L);
		else
			$pdf->Cell(20,4,'',0,0,L);
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(3,4,':   ',0,0,L);
		$pdf->CellFitScale(97,4,$terms[$z],'R',0,L);
		$pdf->SetFont('Arial','B',9);
		$pdf->Cell(70,4,'',0,1,C);	
	}
}

$terms = dotcom_wordwrap($GLOBALS["terms4"],70);
$co=count($terms);

if($co>=1){
	for( $z=0 ; $z<$co ; $z++){
		$pdf->SetFont('Arial','B',8);
		if($z == 0)
			$pdf->Cell(20,4,'Delivery',0,0,L);
		else
			$pdf->Cell(20,4,'',0,0,L);
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(3,4,':   ',0,0,L);
		$pdf->CellFitScale(97,4,$terms[$z],'R',0,L);
		$pdf->SetFont('Arial','B',9);
		$pdf->Cell(70,4,'',0,1,C);
	}
}

$terms = dotcom_wordwrap($GLOBALS["terms5"],70);
$co=count($terms);

if($co>=1){
	for( $z=0 ; $z<$co ; $z++){
		$pdf->SetFont('Arial','B',8);
		if($z == 0)
			$pdf->Cell(20,4,'Payment',0,0,L);
		else
			$pdf->Cell(20,4,'',0,0,L);
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(3,4,':   ',0,0,L);
		$pdf->CellFitScale(97,4,$terms[$z],'R',0,L);
		$pdf->SetFont('Arial','B',9);
		$pdf->Cell(70,4,'',0,1,C);	
	}
}

$terms = dotcom_wordwrap($GLOBALS["terms6"],70);
$co=count($terms);

if($co>=1){
	for( $z=0 ; $z<$co ; $z++){
		$pdf->SetFont('Arial','B',8);
		if($z == 0)
			$pdf->Cell(20,4,'Validity',0,0,L);
		else
			$pdf->Cell(20,4,'',0,0,L);
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(3,4,':   ',0,0,L);
		$pdf->CellFitScale(97,4,$terms[$z],'R',0,L);
		$pdf->SetFont('Arial','B',9);
		$pdf->Cell(70,4,'',0,1,C);		
	}
}

$terms = dotcom_wordwrap($GLOBALS["terms7"],70);
$co=count($terms);

if($co>=1){
	for( $z=0 ; $z<$co ; $z++){
		$pdf->SetFont('Arial','B',8);
		if($z == 0)
			$pdf->Cell(20,4,'Remarks',0,0,L);
		else
			$pdf->Cell(20,4,'',0,0,L);
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(3,4,':   ',0,0,L);
		$pdf->Cell(97,4,$terms[$z],'R',0,L);

		$s_y = $pdf->getY();
		$s_y -= 22;

		$pdf->SetFont('Arial','B',9);
		if($z == ($co-1))
			$pdf->Cell(70,4,'Authorised Signatory',0,1,R);
		else
			$pdf->Cell(70,4,'',0,1,C);
		
	}
	$pdf->Image("../media/company-logos/company_stamp.png",165,$s_y,30,20);

	//$pdf->Image("../media/pdf/signature.png",165,$s_y,30,20);
}

if($tr_flag == 1){
	$pdf->Cell(120,3,'','BR',0,L);
	$pdf->Cell(70,3,'','B',1,L);
}else{
	$pdf->Cell(120,3,'','R',0,L);
	$pdf->Cell(70,3,'','',1,L);
}

$name = "Quotation_-".substr($GLOBALS["q_no"],6,4)."_".str_replace('-','',$GLOBALS["dt"]).".pdf";
// Quotation_AICQ-0006_06042020

$filename = "../pdf/quotations/".$name;
if($pdf_type == 'print'){
	$pdf->AutoPrint();
	$pdf->output('I',$name);
}else{
	$pdf->output('D',$name);
}
// $pdf->Output('F', $filename, true);


?>
 