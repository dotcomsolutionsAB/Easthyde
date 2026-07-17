<?php

function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}

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
		$this->Cell(60,7,$GLOBALS['label'],0,1,L);

		$this->SetFont('Arial','B',18);
		$this->Cell(190,8,'                 INDUSTRIAL CORPORATION',0,2,C);
		$this->SetFont('Arial','',9);
		$this->Cell(190,4,'83/85 NETAJI SUBHASH ROAD, ROOM #A33, GROUND FLOOR',0,2,C);
		$this->Cell(190,4,'KOLKATA - 700 001, WEST BENGAL, INDIA',0,2,C);
		$this->Cell(190,4,'GST : 19AEKPB4862M1Z2',0,1,C);

	    $this->Image("../media/pdf/a_logo.png",38,11,38,16);
	    $this->Image("../media/pdf/contact.jpg",10,40,5,5);
	    $this->Image("../media/pdf/email.jpg",95,40,5,5);
	    $this->Image("../media/pdf/whatsapp.jpg",173,40,5,5);

	    $this->Image("../media/pdf/smc.jpg",10,10,25,10);
	    $this->Image("../media/pdf/uflow.jpg",175,10,25,10);


		$this->Cell(190,6,'     :(033) 2231-6239/7134-2823/4602-7368                                 :info@ammarindustrial.in                                                :7980684655','B',2);
		

		$y = $this->getY();

		$this->SetFont('Arial','B',9);
		$this->Cell(90,3,'','R',2,L);
		$this->Cell(90,5,'Billing Details :','R',2,L);
		$this->SetFont('Arial','B',9);
		$this->CellFitScale(90,5,$GLOBALS["client"],'R',2,L);
		$this->SetFont('Arial','',9);
		$this->CellFitScale(90,5,$GLOBALS["add1"],'R',2,L);
		$tmp = $GLOBALS["add2"];
		$this->CellFitScale(90,5,$tmp,'R',2,L);
		$tmp = $GLOBALS["state"].' - '.$GLOBALS["add3"];
		$this->CellFitScale(90,5,$tmp,'R',2,L);
		$temp = 'GSTIN / UIN : '.$GLOBALS["gstin"];
		$this->Cell(90,5,$temp,'R',2,L);
		$this->SetFont('Arial','B',9);
		$this->Cell(90,5,'','R',2,L);
		$this->Cell(90,7,'Shipping Details','R',2,L);
		$this->SetFont('Arial','',9);
		$this->CellFitScale(90,5,$GLOBALS["ship1"],'R',2,L);
		$this->CellFitScale(90,5,$GLOBALS["ship2"],'R',2,L);
		$this->CellFitScale(90,5,$GLOBALS["ship3"],'R',2,L);
		$this->Cell(90,3,'','RB',2,L);

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

		$this->Image("../media/pdf/quot_bottom.jpg",10,263,190,24);
		$this->Line(10,262,200,262);
	    // Position at 1.5 cm from bottom
	    $this->SetY(-15);
	    // Arial italic 8
	    $this->SetFont('Arial','I',8);
	    // Page number
	    $this->Cell(0,20,'Page '.$GLOBALS["pages"],0,0,'C');
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

$si_no = 'AIC/GST-0009/20-21';
$pdf_type = 'print';

$start = 1;
$copies = 1;
if($_REQUEST['si_start'] != '')
	$start = $_REQUEST['si_start'];
if($_REQUEST['si_copies'] != '')
	$copies = $_REQUEST['si_copies'];

$start = 1;
$copies = 4;

$sql = "SELECT * FROM sales_invoice WHERE `si_no` = '$si_no'";
$query = $db->query($sql);
$row = $query->fetch_assoc();

$show_hsn = $row['hsn_table'];

$shipping = json_decode($row['shipping'], true);


$client = $row['client_name'];
$items = json_decode($row['items'], true);
$invoice_details = json_decode($row['invoice_details'], true);

$sql_temp = "SELECT * FROM clients WHERE name = '$client'";
$query_temp = $db->query($sql_temp);
$row_temp = $query_temp->fetch_assoc();

$address = json_decode($row_temp['address'], true);

$GLOBALS["si_no"] = $si_no;
$GLOBALS["dt"] = date('d-m-Y', strtotime($row['si_date']));

$GLOBALS['client'] = $client;
$GLOBALS['client'] = $client;
$GLOBALS['add1'] = $address["address1"];
$GLOBALS['add2'] = $address["address2"];
$GLOBALS['add3'] = $address["address3"];
$GLOBALS['state'] = $row_temp["state"];

$GLOBALS['ship1'] = $shipping["address1"];
$GLOBALS['ship2'] = $shipping["address2"];
$GLOBALS['ship3'] = $shipping["address3"];

$GLOBALS['buyer_order'] = $invoice_details["buyer_order"];
$GLOBALS['order_date'] = date('d-m-Y', strtotime($invoice_details["order_date"]));
$GLOBALS['payment_terms'] = $invoice_details["payment_terms"];
$GLOBALS['other_ref'] = $invoice_details["other_ref"];
$GLOBALS['delivery_terms'] = $invoice_details["delivery_terms"];


$GLOBALS['despatch_medium'] 	= $invoice_details["despatch_medium"];
$GLOBALS['despatch_doc_no'] 	= $invoice_details["despatch_doc_no"];
if($invoice_details["despatch_date"] != '1970-01-01')
	$GLOBALS['despatch_date'] 		= date('d-m-Y', strtotime($invoice_details["despatch_date"]));
else
	$GLOBALS['despatch_date'] 		= '';
$GLOBALS['despatch_destination']= $invoice_details["despatch_destination"];

$GLOBALS['gstin'] = $row_temp["gstin"];


$state_flag = 1;

if($row_temp["state"] == 'WEST BENGAL'){
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


	$pdf->AddPage();
	$GLOBALS["pages"] = 1;
	// $GLOBALS["total"] = $pdf->totalPages;
	// debug_to_console($pdf->AliasNbPages());

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

	$l = sizeof($items['product']);

	//Printing All Items
	for($i=0;$i<$l;$i++){
		$pos = $i+1;

		if($state_flag == 0)
		{
			$tax = $items['tax'][$i]/2;
			$tax_amount = $items['tax_amount'][$i] / 2;
			$pr = $items['product'][$i];
			$make = $items['group'][$i];

			$line_total = $items['quantity'][$i]*$items['price'][$i]*(100-$items['discount'][$i])/100;
			$GLOBALS["gross_total"] += $line_total;

			$sql_make = "SELECT * FROM product WHERE name = '$pr'";
			$query_make = $db->query($sql_make);
			$row_make = $query_make->fetch_assoc();
			$pr_group = strtoupper($row_make['group']);

			$temp = $items['desc'][$i];
			$product = dotcom_wordwrap($temp,40);
			$co = (is_array($product) ? count($product) : 1);

			if($make == '1')
				$temp = $items['product'][$i].', Make : '.$pr_group;
			else
				$temp = $items['product'][$i];

			$desc = dotcom_wordwrap($temp,40);
			$co_2 = count($desc);

			$description_array = explode('|', $items['long_desc'][$i]);
			$len = sizeof($description_array);

			$limit = $co * 5 + $co_2 * 5 + $len * 3;

			$tmp_y = $pdf->getY();
			$product_limit = 297 - 35 - $limit;
			if($tmp_y > $product_limit){
				$pdf->AddPage();
				$GLOBALS["pages"]++;
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
			$pdf->Cell(63,5,$product[0],'R',0,L);
			$pdf->SetFont('Arial','',8);
			$pdf->CellFitScale(10,5,$items['hsn'][$i],'R',0,C);
			$pdf->CellFitScale(10,5,$items['quantity'][$i],'R',0,C);
			$pdf->CellFitScale(10,5,strtoupper($items['unit'][$i]),'R',0,C);
			if($items['price'][$i] > 0)
			{
				$pdf->CellFitScale(17,5,money_format('%!i', $items['price'][$i]),'R',0,R);
				if($items['discount'][$i] != '')
					$pdf->CellFitScale(10,5,money_format('%!i', $items['discount'][$i]),'R',0,C);
				else
					$pdf->CellFitScale(10,5,money_format('%!i', '0'),'R',0,C);
				$temp = $tax.' %';
				$pdf->Cell(8,5,$temp,'R',0,C);
				$pdf->CellFitScale(12,5,money_format('%!i', $tax_amount),'R',0,C);
				$pdf->Cell(8,5,$temp,'R',0,C);
				$pdf->CellFitScale(12,5,money_format('%!i', $tax_amount),'R',0,C);
			}else{
				$pdf->CellFitScale(17,5,'','R',0,R);
				$pdf->CellFitScale(10,5,'','R',0,R);
				$pdf->Cell(8,5,'','R',0,C);
				$pdf->CellFitScale(12,5,'','R',0,C);
				$pdf->Cell(8,5,'','R',0,C);
				$pdf->CellFitScale(12,5,'','R',0,C);
			}
			$pdf->CellFitScale(23,5,money_format('%!i', $line_total),'',1,R);	

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
			$tax = $items['tax'][$i];
			$tax_amount = $items['tax_amount'][$i];
			$pr = $items['product'][$i];
			$make = $items['group'][$i];

			$line_total = $items['quantity'][$i]*$items['price'][$i]*(100-$items['discount'][$i])/100;
			$GLOBALS["gross_total"] += $line_total;

			$sql_make = "SELECT * FROM product WHERE name = '$pr'";
			$query_make = $db->query($sql_make);
			$row_make = $query_make->fetch_assoc();
			$pr_group = strtoupper($row_make['group']);

			$temp = $items['desc'][$i];
			$product = dotcom_wordwrap($temp,40);
			$co = (is_array($product) ? count($product) : 1);

			if($make == '1')
				$temp = $items['product'][$i].', Make : '.$pr_group;
			else
				$temp = $items['product'][$i];

			$desc = dotcom_wordwrap($temp,40);
			$co_2 = count($desc);

			$description_array = explode('|', $items['long_desc'][$i]);
			$len = sizeof($description_array);

			$limit = $co * 5 + $co_2 * 5 + $len * 3;

			$tmp_y = $pdf->getY();
			$product_limit = 297 - 35 - $limit;
			if($tmp_y > $product_limit){
				$pdf->AddPage();
				$GLOBALS["pages"]++;
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
			$pdf->Cell(63,5,$product[0],'R',0,L);
			$pdf->SetFont('Arial','',8);
			$pdf->CellFitScale(10,5,$items['hsn'][$i],'R',0,C);
			$pdf->CellFitScale(10,5,$items['quantity'][$i],'R',0,C);
			$pdf->CellFitScale(10,5,strtoupper($items['unit'][$i]),'R',0,C);
			if($items['price'][$i] > 0)
			{
				$pdf->CellFitScale(17,5,money_format('%!i', $items['price'][$i]),'R',0,R);
				if($items['discount'][$i] != '')
					$pdf->CellFitScale(10,5,money_format('%!i', $items['discount'][$i]),'R',0,C);
				else
					$pdf->CellFitScale(10,5,money_format('%!i', '0'),'R',0,C);
				$temp = $tax.' %';
				$pdf->Cell(20,5,$temp,'R',0,C);
				$pdf->CellFitScale(20,5,money_format('%!i', $tax_amount),'R',0,C);
			}else{
				$pdf->CellFitScale(17,5,'','R',0,R);
				$pdf->Cell(10,5,'','R',0,C);
				$pdf->CellFitScale(20,5,'','R',0,C);
				$pdf->Cell(20,5,'','R',0,C);
			}
			$pdf->CellFitScale(23,5,money_format('%!i', $line_total),'',1,R);	

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
			$tax_details['taxable'][$pos] += $items['amount'][$i] - $items['tax_amount'][$i];
			$tax_details['cgst'][$pos] += $items['tax_amount'][$i] / 2;
			$tax_details['sgst'][$pos] += $items['tax_amount'][$i] / 2;
			$tax_details['igst'][$pos] += $items['tax_amount'][$i];
			$tax_details['total'][$pos] += $items['tax_amount'][$i];

		}else{
			$tax_details['hsn'][] = $items['hsn'][$i];
			$tax_details['rate'][] = $items['tax'][$i];
			$tax_details['taxable'][] = $items['amount'][$i] - $items['tax_amount'][$i];
			$tax_details['cgst'][] = $items['tax_amount'][$i] / 2;
			$tax_details['sgst'][] = $items['tax_amount'][$i] / 2;
			$tax_details['igst'][] = $items['tax_amount'][$i];
			$tax_details['total'][] = $items['tax_amount'][$i];
		}

	}

	//Addons
	$pdf->Cell(167,3,'','TR',0,C);
	$pdf->Cell(23,3,'','T',1,C);

	$t_tax = $items['tax'][0];
	$t2_tax = $t_tax/2;

	$addons_array = json_decode($row['addons'], true);

	$pdf->Cell(95,5,'',0,0,L);
	$pdf->SetFont('Arial','I',9);
	$pdf->Cell(72,5,'Gross Total','R',0,L);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(23,5,money_format('%!i',$GLOBALS["gross_total"]),0,1,R);

	if($addons_array['pf']!='' && $addons_array['pf'] > 0){
		$pdf->Cell(95,5,'',0,0,L);
		$pdf->SetFont('Arial','I',9);
		$pdf->Cell(72,5,'Add   : Packaging & Forwarding','R',0,L);
		$pdf->SetFont('Arial','',9);
		$pdf->Cell(23,5,money_format('%!i',$addons_array['pf']),0,1,R);

		$hsn = '8407';
		$pos = '-1';
		$len = sizeof($tax_details['hsn']);
		for($j=0;$j<$len;$j++){
			if($tax_details['hsn'][$j] == $hsn){
				$pos = $j;
				break;
			}
		}

		if($pos != '-1'){
			$tax_details['taxable'][$pos] += $addons_array['pf'];
			$tax_details['cgst'][$pos] += $addons_array['pf'] * 9 / 100;
			$tax_details['sgst'][$pos] += $addons_array['pf'] * 9 / 100;
			$tax_details['igst'][$pos] += $addons_array['pf'] * 18 / 100;
			$tax_details['total'][$pos] += $addons_array['pf'] * 18 / 100;

		}else{
			$tax_details['hsn'][] = $hsn;
			$tax_details['rate'][] = '18';
			$tax_details['taxable'][] = $addons_array['pf'];
			$tax_details['cgst'][] = $addons_array['pf'] * 9 / 100;
			$tax_details['sgst'][] = $addons_array['pf'] * 9 / 100;
			$tax_details['igst'][] = $addons_array['pf'] * 18 / 100;
			$tax_details['total'][] = $addons_array['pf'] * 18 / 100;
		}
	}

	if($addons_array['freight']!='' && $addons_array['freight'] > 0){
		$pdf->Cell(95,5,'',0,0,L);
		$pdf->SetFont('Arial','I',9);
		$pdf->Cell(72,5,'Add   : Freight','R',0,L);
		$pdf->SetFont('Arial','',9);
		$pdf->Cell(23,5,money_format('%!i',$addons_array['freight']),0,1,R);

		$hsn = '8407';
		$pos = '-1';
		$len = sizeof($tax_details['hsn']);
		for($j=0;$j<$len;$j++){
			if($tax_details['hsn'][$j] == $hsn){
				$pos = $j;
				break;
			}
		}

		if($pos != '-1'){
			$tax_details['taxable'][$pos] += $addons_array['freight'];
			$tax_details['cgst'][$pos] += $addons_array['freight'] * 9 / 100;
			$tax_details['sgst'][$pos] += $addons_array['freight'] * 9 / 100;
			$tax_details['igst'][$pos] += $addons_array['freight'] * 18 / 100;
			$tax_details['total'][$pos] += $addons_array['freight'] * 18 / 100;

		}else{
			$tax_details['hsn'][] = $hsn;
			$tax_details['rate'][] = '18';
			$tax_details['taxable'][] = $addons_array['freight'];
			$tax_details['cgst'][] = $addons_array['freight'] * 9 / 100;
			$tax_details['sgst'][] = $addons_array['freight'] * 9 / 100;
			$tax_details['igst'][] = $addons_array['freight'] * 18 / 100;
			$tax_details['total'][] = $addons_array['freight'] * 18 / 100;
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
		$pdf->Cell(95,5,'',0,0,L);
		$pdf->Cell(72,5,'Add   : CGST','R',0,L);
		$pdf->SetFont('Arial','',9);
		$pdf->Cell(23,5,money_format('%!i', $cgst),0,1,R);

		$pdf->Cell(95,5,'',0,0,L);
		$pdf->SetFont('Arial','I',9);
		$pdf->Cell(72,5,'Add   : SGST','R',0,L);
		$pdf->SetFont('Arial','',9);
		$pdf->Cell(23,5,money_format('%!i', $sgst),0,1,R);
	}else{
		$pdf->SetFont('Arial','I',9);
		$pdf->Cell(95,5,'',0,0,L);
		$pdf->Cell(72,5,'Add   : IGST','R',0,L);
		$pdf->SetFont('Arial','',9);
		$pdf->Cell(23,5,money_format('%!i', $igst),0,1,R);
	}

	if($addons_array['discount']!= '' && $addons_array['discount'] > 0)
	{
		$pdf->Cell(95,5,'',0,0,L);
		$pdf->SetFont('Arial','I',9);
		$pdf->Cell(72,5,'Less   : Discount','R',0,L);
		$pdf->SetFont('Arial','',9);
		$pdf->Cell(23,5,money_format('%!i',$addons_array['discount']),0,1,R);
	}

	if($addons_array['roundoff']!='' && $addons_array['roundoff'] != 0)
	{
		if($addons_array['roundoff'] < 0){
			$roundoff_temp = $addons_array['roundoff'] * -1;
			$pdf->Cell(95,5,'',0,0,L);
			$pdf->SetFont('Arial','I',9);
			$pdf->Cell(72,5,'Less : Rounded Off (-)','R',0,L);
			$pdf->SetFont('Arial','',9);
			$pdf->Cell(23,5,money_format('%!i',$roundoff_temp),0,1,R);
		}else{
			$pdf->Cell(95,5,'',0,0,L);
			$pdf->SetFont('Arial','I',9);
			$pdf->Cell(72,5,'Add : Rounded Off (+)','R',0,L);
			$pdf->SetFont('Arial','',9);
			$pdf->Cell(23,5,money_format('%!i',$addons_array['roundoff']),0,1,R);
		}
	}

	$pdf->Cell(167,3,'','BR',0,C);
	$pdf->Cell(23,3,'','B',1,C);
	//End Addons

	$pdf->SetFont('Arial','B',9);

	$pdf->Cell(80,7,'',0,0,R);
	$pdf->Cell(10,7,$grand_total_qty,'B',0,C);
	$pdf->Cell(77,7,'GRAND TOTAL',0,0,R);

	if($state_flag == '0'){
		$total_amount = $GLOBALS["gross_total"] + $addons_array['pf'] + $addons_array['freight'] + $sgst + $cgst - $addons_array['discount'] + $addons_array['roundoff'];
	}else{
		$total_amount = $GLOBALS["gross_total"] + $addons_array['pf'] + $addons_array['freight'] + $igst - $addons_array['discount'] + $addons_array['roundoff'];
	}

	$pdf->Cell(23,7,money_format('%!i', $total_amount),'LB',1,R);

	$pdf->SetFont('Arial','B',7);
	$total_amount_words = "Amount Chargeable (in words) : ".convertToIndianCurrency($total_amount);
	$pdf->Cell(190,10,$total_amount_words,0,1,L);

	//--------------------------------------------------- HSN Wise Summary --------------------------------------------------

	if($show_hsn == '1')
	{
		$len = sizeof($tax_details['hsn']);

		$tmp_y = $pdf->getY();
		$hsn_limit = 297 - 13 - ($len*5) - 40;
		if($tmp_y > $hsn_limit)
			$pdf->AddPage();
			$GLOBALS["pages"]++;

		if($state_flag == '0'){
			$pdf->SetFont('Arial','B',7);
			$pdf->Cell(190,3,'',0,1,C);
			$pdf->Cell(75,5,'HSN/SAC','TBR',0,C);
			$pdf->Cell(20,5,'Tax Rate','TBR',0,C);
			$pdf->Cell(20,5,'Taxable Amt.','TBR',0,C);
			$pdf->Cell(20,5,'CGST','TBR',0,C);
			$pdf->Cell(20,5,'SGST','TBR',0,C);
			$pdf->Cell(35,5,'Total Tax','TB',1,C);

			$tot_taxable = 0; $tot_cgst = 0; $tot_sgst = 0; $tot_total = 0;

			for($i=0;$i<$len;$i++){
				$pdf->SetFont('Arial','',7);
				$pdf->Cell(75,5,$tax_details['hsn'][$i],'R',0,C);
				$temp = $tax_details['rate'][$i].'%';
				$pdf->Cell(20,5,$temp,'R',0,C);
				$pdf->Cell(20,5,money_format('%!i', $tax_details['taxable'][$i]),'R',0,C);
				$pdf->Cell(20,5,money_format('%!i', $tax_details['cgst'][$i]),'R',0,C);
				$pdf->Cell(20,5,money_format('%!i', $tax_details['sgst'][$i]),'R',0,C);
				$pdf->Cell(35,5,money_format('%!i', $tax_details['total'][$i]),'',1,C);

				$tot_taxable += $tax_details['taxable'][$i];
				$tot_cgst += $tax_details['cgst'][$i];
				$tot_sgst += $tax_details['sgst'][$i];
				$tot_total += $tax_details['total'][$i];
			}

			$pdf->SetFont('Arial','B',7);
			$pdf->Cell(75,5,'Totals','TBR',0,R);
			$pdf->Cell(20,5,'','TBR',0,C);
			$pdf->Cell(20,5,money_format('%!i', $tot_taxable),'TBR',0,C);
			$pdf->Cell(20,5,money_format('%!i', $tot_cgst),'TBR',0,C);
			$pdf->Cell(20,5,money_format('%!i', $tot_sgst),'TBR',0,C);
			$pdf->Cell(35,5,money_format('%!i', $tot_total),'TB',1,C);
		}
		else{
			$pdf->SetFont('Arial','B',7);
			$pdf->Cell(190,3,'',0,1,C);
			$pdf->Cell(75,5,'HSN/SAC','TBR',0,C);
			$pdf->Cell(25,5,'Tax Rate','TBR',0,C);
			$pdf->Cell(25,5,'Taxable Amt.','TBR',0,C);
			$pdf->Cell(30,5,'IGST','TBR',0,C);
			$pdf->Cell(35,5,'Total Tax','TB',1,C);

			$tot_taxable = 0; $tot_igst = 0; $tot_total = 0;

			for($i=0;$i<$len;$i++){
				$pdf->SetFont('Arial','',7);
				$pdf->Cell(75,5,$tax_details['hsn'][$i],'R',0,C);
				$temp = $tax_details['rate'][$i].'%';
				$pdf->Cell(25,5,$temp,'R',0,C);
				$pdf->Cell(25,5,money_format('%!i', $tax_details['taxable'][$i]),'R',0,C);
				$pdf->Cell(30,5,money_format('%!i', $tax_details['igst'][$i]),'R',0,C);
				$pdf->Cell(35,5,money_format('%!i', $tax_details['total'][$i]),'',1,C);

				$tot_taxable += $tax_details['taxable'][$i];
				$tot_igst += $tax_details['igst'][$i];
				$tot_total += $tax_details['total'][$i];
			}

			$pdf->SetFont('Arial','B',7);
			$pdf->Cell(75,5,'Totals','TBR',0,C);
			$pdf->Cell(25,5,'','TBR',0,C);
			$pdf->Cell(25,5,money_format('%!i', $tot_taxable),'TBR',0,C);
			$pdf->Cell(30,5,money_format('%!i', $tot_igst),'TBR',0,C);
			$pdf->Cell(35,5,money_format('%!i', $tot_total),'TB',1,C);
		}

		$tax_amount_words = "Tax Amount (in words) : ".convertToIndianCurrency($tot_total);
		$pdf->Cell(190,8,$tax_amount_words,'',1,L);
	}

	$tr_flag = 0;

	$tmp_y = $pdf->getY();
	$terms_limit = 297 - 65;
	if($tmp_y > $terms_limit){
		$pdf->AddPage();
		$GLOBALS["pages"]++;
		$tr_flag=1;
		// $pdf->setY($terms_limit);
	}else{
		$pdf->setY($terms_limit);
	}

	$pdf->SetFont('Arial','',9);
	$pdf->Cell(190,8,'BANK NAME : HDFC BANK, BRANCH : DALHOUSIE-CLIVE ROW, A/C NO. : 50200030584202, IFSC :HDFC0001015','TB',2,C);

	$y = $pdf->getY();
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(95,1,'','TR',0,L);
	$pdf->Cell(95,1,'','T',1,L);
	$pdf->Cell(95,5,'Customer\'s Seal and Signature:','R',0,L);
	$pdf->Cell(95,5,'for Easthyde',0,1,R);
	$pdf->Cell(95,12,'','R',0,L);
	$pdf->Cell(95,12,'',0,1,R);
	$pdf->Cell(95,4,'','R',0,L);
	$pdf->Cell(95,4,'Authorised Signatory',0,1,R);

	// if($tr_flag == 1){
	// 	$pdf->Cell(95,3,'','BR',0,L);
	// 	$pdf->Cell(95,3,'','B',1,L);
	// }else{
	// 	$pdf->Cell(95,3,'','R',0,L);
	// 	$pdf->Cell(95,3,'','',1,L);
	// }
}


//------------------------------------------------- Terms & Conditions Block ------------------------------------------------------

$name = "Invoice_AICGST-".substr($GLOBALS["si_no"],8,4)."_".str_replace('-','',$GLOBALS["dt"]).".pdf";

if($pdf_type == 'print'){
	// $pdf->AutoPrint();
	$pdf->output('I',$name);
}else{
	// $pdf->output('D',$name);
	$pdf->output('I',$name);
}

?>
