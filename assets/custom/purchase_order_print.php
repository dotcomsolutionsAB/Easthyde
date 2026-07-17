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
		
	    // $this->Image("../media/pdf/quot_top.jpg",10,10,190,20);
		// $this->Cell(190,20,'','B',2,C);

		$this->SetFont('Arial','U',15);
		$this->Cell(190,3,'',0,2,C);
		$this->Cell(190,7,'Purchase Order',0,2,C);

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
		$this->Cell(190,4,'GST : 19ALCPM0139R1ZO',B,1,C);

		$this->Image("../media/company-logos/logo.jpg",10,20,50,20);
		$this->Image("../media/company-logos/MSME1.png",160,13,40,35);
	    // $this->Image("../media/pdf/contact.jpg",10,40,3,3);
	    $this->Image("../media/pdf/email.jpg",80,36,3,3);
	    $this->Image("../media/pdf/whatsapp.jpg",83,40,3,3);
		

		$y = $this->getY();

		$this->SetFont('Arial','B',9);
		$this->Cell(90,2,'','R',2,L);
		$this->Cell(90,5,'Supplier Details :','R',2,L);
		$this->SetFont('Arial','B',9);
		$this->CellFitScale(90,5,$GLOBALS["supplier"],'R',2,L);
		$this->SetFont('Arial','',9);
		$this->CellFitScale(90,5,$GLOBALS["add1"],'R',2,L);
		$tmp = $GLOBALS["add2"];
		$this->CellFitScale(90,5,$tmp,'R',2,L);
		$tmp = $GLOBALS["city"].' - '.$GLOBALS["pincode"].', '.$GLOBALS["state"].', '.$GLOBALS["country"];
		$this->CellFitScale(90,5,$tmp,'R',2,L);
		$temp = 'GSTIN / UIN : '.$GLOBALS["gstin"];
		$this->Cell(90,5,$temp,'RB',2,L);
		$this->Cell(90,3,'','R',2,L);
		$this->SetFont('Arial','B',9);
		$this->Cell(90,5,'Shipping Details :','R',2,L);
		$this->SetFont('Arial','B',9);
		$this->CellFitScale(90,5,$GLOBALS["ship_supplier"],'R',2,L);
		$this->SetFont('Arial','',9);
		$this->CellFitScale(90,5,$GLOBALS["ship_add1"],'R',2,L);
		$tmp = $GLOBALS["ship_add2"];
		$this->CellFitScale(90,5,$tmp,'R',2,L);
		$tmp = $GLOBALS["ship_city"].' - '.$GLOBALS["ship_pincode"].', '.$GLOBALS["ship_state"].', '.$GLOBALS["ship_country"];
		$this->CellFitScale(90,5,$tmp,'R',2,L);
		$this->Cell(90,3,'','RB',2,L);

		$this->setXY('102',$y);

		$this->Cell(100,2,'',0,2,L);
		$this->CellFitScale(35,5,'Purchase Order No.',0,0,L);
		$temp = ':   '.$GLOBALS["po_no"];
		$this->CellFitScale(65,5,$temp,0,1,L);
		$this->setX('102');
		$this->CellFitScale(35,5,'Dated',0,0,L);
		$temp = ':   '.$GLOBALS["dt"];
		$this->CellFitScale(65,5,$temp,0,1,L);
		$this->setX('102');
		$this->CellFitScale(35,5,'Mode / Terms of Payment',0,0,L);
		$temp = ':   '.$GLOBALS["mode"];
		$this->CellFitScale(65,5,$temp,0,1,L);
		$this->setX('102');
		$this->CellFitScale(35,5,'Supplier\'s Reference Number',0,0,L);
		$temp = ':   '.$GLOBALS["suppier_ref"];
		$this->CellFitScale(65,5,$temp,0,1,L);
		$this->setX('102');
		$this->CellFitScale(35,5,'Other Reference(s)',0,0,L);
		$temp = ':   '.$GLOBALS["other_ref"];
		$this->CellFitScale(65,5,$temp,0,1,L);
		$this->setX('102');
		$this->CellFitScale(35,5,'Despatch Through',0,0,L);
		$temp = ':   '.$GLOBALS["despatch"];
		$this->CellFitScale(65,5,$temp,0,1,L);
		$this->setX('102');
		$this->CellFitScale(35,5,'Destination',0,0,L);
		$temp = ':   '.$GLOBALS["destination"];
		$this->CellFitScale(65,5,$temp,0,1,L);
		$this->setX('102');
		$this->CellFitScale(35,5,'Terms of Delivery',0,0,L);
		$temp = ':   '.$GLOBALS["terms"];
		$this->CellFitScale(65,5,$temp,0,1,L);
		$this->setX('102');
		$y = $this->getY();
		$this->setXY('100',$y);
		$this->Cell(100,18,'',0,1,L);
		$y = $this->getY();
		$this->setXY('100',$y);
		$this->Cell(100,3,'','B',1,L);
	}

	// Page footer
	function Footer()
	{

		// $this->Image("../media/pdf/quot_bottom.jpg",10,263,190,24);
		// $this->Line(10,262,200,262);
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

$po_no = $_REQUEST['id'] ?? '';
$pdf_type = $_REQUEST['type'] ?? '';

$sql = "SELECT * FROM purchase_order WHERE `po_no` = '$po_no'";
$query = $db->query($sql);
if (!$query || !($row = $query->fetch_assoc())) {
	die('Record not found');
}

$supplier = $row['supplier_name'] ?? '';
$items = json_decode($row['items'] ?? '', true);
if (!is_array($items)) { $items = []; }

$shipping = json_decode($row['shipping'] ?? '', true);
if (!is_array($shipping)) { $shipping = []; }
$top = json_decode($row['top'] ?? '', true);
if (!is_array($top)) { $top = []; }

$sql_temp = "SELECT * FROM suppliers WHERE name = '$supplier'";
$query_temp = $db->query($sql_temp);
$row_temp = $query_temp ? $query_temp->fetch_assoc() : null;

$address = [];
if ($row_temp) {
	$address = json_decode($row_temp['address'] ?? '', true);
}
if (!is_array($address)) { $address = []; }

$GLOBALS["gross_total"] = '0';
$GLOBALS["po_no"] = $po_no;
$GLOBALS["dt"] = !empty($row['po_date']) ? date('d-m-Y', strtotime($row['po_date'])) : '';

$GLOBALS['supplier'] = $supplier;
$GLOBALS['add1'] = $address["address_1"] ?? '';
$GLOBALS['add2'] = $address["address_2"] ?? '';
$GLOBALS['city'] = $address["city"] ?? '';
$GLOBALS['pincode'] = $address["pincode"] ?? '';
$GLOBALS['state'] = $row_temp["state"] ?? '';
$GLOBALS['country'] = $row_temp["country"] ?? '';
$GLOBALS['gstin'] = $row_temp["gstin"] ?? '';

$GLOBALS['ship_supplier'] = $shipping['name'] ?? '';
$GLOBALS['ship_add1'] 	= $shipping["address_1"] ?? '';
$GLOBALS['ship_add2'] 	= $shipping["address_2"] ?? '';
$GLOBALS['ship_city'] 	= $shipping["city"] ?? '';
$GLOBALS['ship_pincode']= $shipping["pincode"] ?? '';
$GLOBALS['ship_state'] 	= $row["state"] ?? '';
$GLOBALS['ship_country']= $shipping["country"] ?? '';

$GLOBALS['mode'] 		= $top['mode'] ?? '';
$GLOBALS['suppier_ref']	= $top["supplier_ref"] ?? '';
$GLOBALS['other_ref'] 	= $top["other_ref"] ?? '';
$GLOBALS['despatch'] 	= $top["despatch"] ?? '';
$GLOBALS['destination']	= $top["destination"] ?? '';
$GLOBALS['terms'] 		= $top["terms"] ?? '';

$flag = 1;

if(($row_temp["state"] ?? '') == 'WEST BENGAL'){
	$flag = 0;
}

$pdf = new PDF_AutoPrint();
$pdf->SetAutoPageBreak(true, 10);
$pdf->setMargins(10, 10);
$title = "Purchase Order";
$pdf->SetTitle($title);

$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->setX('10');

//------------------------------------------------------ Table Header ---------------------------------------------------------------
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
		$pr = $items['product'][$i];
		$make = $items['group'][$i];

		$line_total = (float)($items['quantity'][$i] ?? 0)*(float)($items['price'][$i] ?? 0)*(100-(float)($items['discount'][$i] ?? 0))/100;
		$GLOBALS["gross_total"] += $line_total;

		$sql_make = "SELECT * FROM product WHERE name = '$pr'";
		$query_make = $db->query($sql_make);
		$row_make = $query_make ? $query_make->fetch_assoc() : null;
		$pr_group = strtoupper((string)($row_make['group'] ?? ''));

		$temp = $items['product'][$i];
		$product = dotcom_wordwrap($temp,40);
		$co = (is_array($product) ? count($product) : 1);

		if($make == '1')
			$temp = $items['desc'][$i].', Make : '.$pr_group;
		else
			$temp = $items['desc'][$i];

		$desc = dotcom_wordwrap($temp,40);
		$co_2 = count($desc);

		$description_array = explode('|', (string)($items['long_desc'][$i] ?? ''));
		$len = sizeof($description_array);

		$limit = $co * 5 + $co_2 * 5 + $len * 3;

		$tmp_y = $pdf->getY();
		$product_limit = 297 - $limit;
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
		$pdf->Cell(63,5,$product[0],'R',0,L);
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
			for( $z=1 ; $z<$co ; $z++){
				$pdf->Cell(7,5,'','R',0,C);
				$pdf->SetFont('Arial','B',7);
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
		}

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
		$pr = $items['product'][$i];
		$make = $items['group'][$i];

		$line_total = (float)($items['quantity'][$i] ?? 0)*(float)($items['price'][$i] ?? 0)*(100-(float)($items['discount'][$i] ?? 0))/100;
		$GLOBALS["gross_total"] += $line_total;

		$sql_make = "SELECT * FROM product WHERE name = '$pr'";
		$query_make = $db->query($sql_make);
		$row_make = $query_make ? $query_make->fetch_assoc() : null;
		$pr_group = strtoupper((string)($row_make['group'] ?? ''));

		$temp = $items['product'][$i];
		$product = dotcom_wordwrap($temp,40);
		$co = (is_array($product) ? count($product) : 1);

		if($make == '1')
			$temp = $items['desc'][$i].', Make : '.$pr_group;
		else
			$temp = $items['desc'][$i];

		$desc = dotcom_wordwrap($temp,40);
		$co_2 = count($desc);

		$description_array = explode('|', (string)($items['long_desc'][$i] ?? ''));
		$len = sizeof($description_array);

		$limit = $co * 5 + $co_2 * 5 + $len * 3;

		$tmp_y = $pdf->getY();
		$product_limit = 297 - $limit;
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
		$pdf->Cell(63,5,$product[0],'R',0,L);
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
			for( $z=1 ; $z<$co ; $z++){
				$pdf->Cell(7,5,'','R',0,C);
				$pdf->SetFont('Arial','B',7);
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
		}

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
		$tax_details['hsn'][] = $items['hsn'][$i];
		$tax_details['rate'][] = (float)($items['tax'][$i] ?? 0);
		$tax_details['taxable'][] = $line_total;
		$tax_details['cgst'][] = (float)($items['cgst'][$i] ?? 0);
		$tax_details['sgst'][] = (float)($items['sgst'][$i] ?? 0);
		$tax_details['igst'][] = (float)($items['igst'][$i] ?? 0);
		$tax_details['total'][] = (float)($items['cgst'][$i] ?? 0) + (float)($items['sgst'][$i] ?? 0) + (float)($items['igst'][$i] ?? 0);
	}

}

//Addons
$pdf->Cell(167,3,'','TR',0,C);
$pdf->Cell(23,3,'','T',1,C);

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

	if($pos != '-1'){
		$tax_details['taxable'][$pos] += (float)($addons_array['pf']['value'] ?? 0);
		$tax_details['cgst'][$pos] += (float)($addons_array['pf']['cgst'] ?? 0);
		$tax_details['sgst'][$pos] += (float)($addons_array['pf']['sgst'] ?? 0);
		$tax_details['igst'][$pos] += (float)($addons_array['pf']['igst'] ?? 0);
		$tax_details['total'][$pos] += (float)($addons_array['pf']['cgst'] ?? 0) + (float)($addons_array['pf']['sgst'] ?? 0) + (float)($addons_array['pf']['igst'] ?? 0);

	}else{
		$tax_details['hsn'][] = $hsn;
		$tax_details['rate'][] = '18';
		$tax_details['taxable'][] = (float)($addons_array['pf']['value'] ?? 0);
		$tax_details['cgst'][] = (float)($addons_array['pf']['cgst'] ?? 0);
		$tax_details['sgst'][] = (float)($addons_array['pf']['sgst'] ?? 0);
		$tax_details['igst'][] = (float)($addons_array['pf']['igst'] ?? 0);
		$tax_details['total'][] = (float)($addons_array['pf']['cgst'] ?? 0) + (float)($addons_array['pf']['sgst'] ?? 0) + (float)($addons_array['pf']['igst'] ?? 0);
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

	if($pos != '-1'){
		$tax_details['taxable'][$pos] += (float)($addons_array['freight']['value'] ?? 0);
		$tax_details['cgst'][$pos] += (float)($addons_array['freight']['cgst'] ?? 0);
		$tax_details['sgst'][$pos] += (float)($addons_array['freight']['sgst'] ?? 0);
		$tax_details['igst'][$pos] += (float)($addons_array['freight']['igst'] ?? 0);
		$tax_details['total'][$pos] += (float)($addons_array['freight']['cgst'] ?? 0) + (float)($addons_array['freight']['sgst'] ?? 0) + (float)($addons_array['freight']['igst'] ?? 0);

	}else{
		$tax_details['hsn'][] = $hsn;
		$tax_details['rate'][] = '18';
		$tax_details['taxable'][] = (float)($addons_array['freight']['value'] ?? 0);
		$tax_details['cgst'][] = (float)($addons_array['freight']['cgst'] ?? 0);
		$tax_details['sgst'][] = (float)($addons_array['freight']['sgst'] ?? 0);
		$tax_details['igst'][] = (float)($addons_array['freight']['igst'] ?? 0);
		$tax_details['total'][] = (float)($addons_array['freight']['cgst'] ?? 0) + (float)($addons_array['freight']['sgst'] ?? 0) + (float)($addons_array['freight']['igst'] ?? 0);
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
		$roundoff_temp = (float)($addons_array['roundoff'] ?? 0) * -1;
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

if($flag == '0'){
	$total_amount = (float)$GLOBALS["gross_total"] + (float)($addons_array['pf']['value'] ?? 0) + (float)($addons_array['freight']['value'] ?? 0) + (float)$sgst + (float)$cgst + (float)($addons_array['roundoff'] ?? 0);
}else{
	$total_amount = (float)$GLOBALS["gross_total"] + (float)($addons_array['pf']['value'] ?? 0) + (float)($addons_array['freight']['value'] ?? 0) + (float)$igst + (float)($addons_array['roundoff'] ?? 0);
}

$pdf->Cell(23,7,number_format((float)$total_amount, 2),'LB',1,R);

$pdf->SetFont('Arial','',9);

$pdf->Cell(190,10,convertToIndianCurrency($total_amount),0,1,L);

//--------------------------------------------------- HSN Wise Summary -----------------------------------------------------------

$len = sizeof($tax_details['hsn']);

$tmp_y = $pdf->getY();
$hsn_limit = 297 - 13 - ($len*5);
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

	$tr_flag = 0;

	$tmp_y = $pdf->getY();
	$terms_limit = 297 - 35;
	if($tmp_y > $terms_limit){
		$GLOBALS["pages"]++;
		$pdf->AddPage();
		$tr_flag=1;
		// $pdf->setY($terms_limit);
	}else{
		$pdf->setY($terms_limit);
	}

	$y = $pdf->getY();
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(95,1,'','T',0,L);
	$pdf->Cell(95,1,'','T',1,L);
	$pdf->Cell(95,5,'','',0,L);
	$pdf->Cell(95,5,'for M. M. Lucky Enterprises',0,1,R);
	$pdf->Image("../media/company-logos/company_stamp.png",175,265,16,16);
	$pdf->Cell(95,12,'','',0,L);
	$pdf->Cell(95,12,'',0,1,R);
	$pdf->Cell(95,4,'','',0,L);
	$pdf->Cell(95,4,'Authorised Signatory',0,1,R);

	if($tr_flag == 1){
		$pdf->Cell(95,3,'','BR',0,L);
		$pdf->Cell(95,3,'','B',1,L);
	}else{
		// $pdf->Cell(95,3,'','R',0,L);
		// $pdf->Cell(95,3,'','',1,L);
	}
//------------------------------------------------- Terms & Conditions Block ------------------------------------------------------

$name = "Purchase_Order_AICPO-".substr($GLOBALS["po_no"],7,4)."_".str_replace('-','',$GLOBALS["dt"]).".pdf";
if($pdf_type == 'print'){
	$pdf->AutoPrint();
	$pdf->output('I',$name);
}else{
	$pdf->output('D',$name);
}

?>
