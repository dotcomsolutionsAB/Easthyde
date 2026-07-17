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

		$this->SetFont('Arial','B',18);
	    $this->Image("../media/pdf/a_logo.png",38,11,38,16);
		$this->Cell(190,8,'                 INDUSTRIAL CORPORATION',0,2,C);
		$this->SetFont('Arial','',9);
		$this->Cell(190,4,'83/85 NETAJI SUBHASH ROAD, ROOM #A33, GROUND FLOOR',0,2,C);
		$this->Cell(190,4,'KOLKATA - 700 001, WEST BENGAL, INDIA',0,2,C);
		$this->Cell(190,4,'GST : 19AEKPB4862M1Z2',0,1,C);
	    $this->Image("../media/pdf/contact.jpg",10,40,5,5);
	    $this->Image("../media/pdf/email.jpg",95,40,5,5);
	    $this->Image("../media/pdf/whatsapp.jpg",173,40,5,5);

		$this->Cell(190,6,'     :(033) 2231-6239/7134-2823/4602-7368                                 :info@ammarindustrial.in                                                :7980684655','B',2);
		

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

$po_no = $_REQUEST['po_em_id'];
$pdf_type = $_REQUEST['type'];

$sql = "SELECT * FROM purchase_order WHERE `po_no` = '$po_no'";
$query = $db->query($sql);
$row = $query->fetch_assoc();

$supplier = $row['supplier_name'];
$items = json_decode($row['items'], true);

$shipping = json_decode($row['shipping'], true);
$top = json_decode($row['top'], true);

$sql_temp = "SELECT * FROM suppliers WHERE name = '$supplier'";
$query_temp = $db->query($sql_temp);
$row_temp = $query_temp->fetch_assoc();

$address = json_decode($row_temp['address'], true);

$GLOBALS["gross_total"] = '0';
$GLOBALS["po_no"] = $po_no;
$GLOBALS["dt"] = date('d-m-Y', strtotime($row['po_date']));

$GLOBALS['supplier'] = $supplier;
$GLOBALS['add1'] = $address["address_1"];
$GLOBALS['add2'] = $address["address_2"];
$GLOBALS['city'] = $address["city"];
$GLOBALS['pincode'] = $address["pincode"];
$GLOBALS['state'] = $row_temp["state"];
$GLOBALS['gstin'] = $row_temp["gstin"];

$GLOBALS['ship_supplier'] = $shipping['name'];
$GLOBALS['ship_add1'] 	= $shipping["address_1"];
$GLOBALS['ship_add2'] 	= $shipping["address_2"];
$GLOBALS['ship_city'] 	= $shipping["city"];
$GLOBALS['ship_pincode']= $shipping["pincode"];
$GLOBALS['ship_state'] 	= $row["state"];
$GLOBALS['ship_country']= $shipping["country"];

$GLOBALS['mode'] 		= $top['mode'];
$GLOBALS['suppier_ref']	= $top["supplier_ref"];
$GLOBALS['other_ref'] 	= $top["other_ref"];
$GLOBALS['despatch'] 	= $top["despatch"];
$GLOBALS['destination']	= $top["destination"];
$GLOBALS['terms'] 		= $top["terms"];

$flag = 1;

if($row_temp["state"] == 'WEST BENGAL'){
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

$l = sizeof($items['product']);

//Printing All Items
for($i=0;$i<$l;$i++){
	$pos = $i+1;

	if($flag == 0)
	{
		$tax = $items['tax'][$i]/2;
		$cgst = $items['cgst'][$i];
		$sgst = $items['sgst'][$i];
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
			$pdf->CellFitScale(12,5,money_format('%!i', $cgst),'R',0,C);
			$pdf->Cell(8,5,$temp,'R',0,C);
			$pdf->CellFitScale(12,5,money_format('%!i', $sgst),'R',0,C);
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
		$igst = $items['igst'][$i];
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
			$pdf->CellFitScale(20,5,money_format('%!i', $igst),'R',0,C);
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
		$tax_details['taxable'][$pos] += $line_total;
		$tax_details['cgst'][$pos] += $items['cgst'][$i];
		$tax_details['sgst'][$pos] += $items['sgst'][$i];
		$tax_details['igst'][$pos] += $items['igst'][$i];
		$tax_details['total'][$pos] += $items['cgst'][$i] + $items['sgst'][$i] + $items['igst'][$i];

	}else{
		$tax_details['hsn'][] = $items['hsn'][$i];
		$tax_details['rate'][] = $items['tax'][$i];
		$tax_details['taxable'][] = $line_total;
		$tax_details['cgst'][] = $items['cgst'][$i];
		$tax_details['sgst'][] = $items['sgst'][$i];
		$tax_details['igst'][] = $items['igst'][$i];
		$tax_details['total'][] = $items['cgst'][$i] + $items['sgst'][$i] + $items['igst'][$i];
	}

}

//Addons
$pdf->Cell(167,3,'','TR',0,C);
$pdf->Cell(23,3,'','T',1,C);

$addons_array = json_decode($row['addons'], true);

$pdf->Cell(95,5,'',0,0,L);
$pdf->SetFont('Arial','I',9);
$pdf->Cell(72,5,'Gross Total','R',0,L);
$pdf->SetFont('Arial','',9);
$pdf->Cell(23,5,money_format('%!i',$GLOBALS["gross_total"]),0,1,R);

if($addons_array['pf']['value']!='' && $addons_array['pf']['value'] > 0){
	$pdf->Cell(95,5,'',0,0,L);
	$pdf->SetFont('Arial','I',9);
	$pdf->Cell(72,5,'Add   : Packaging & Forwarding','R',0,L);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(23,5,money_format('%!i',$addons_array['pf']['value']),0,1,R);

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

if($addons_array['freight']['value']!='' && $addons_array['freight']['value'] > 0){
	$pdf->Cell(95,5,'',0,0,L);
	$pdf->SetFont('Arial','I',9);
	$pdf->Cell(72,5,'Add   : Freight','R',0,L);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(23,5,money_format('%!i',$addons_array['freight']['value']),0,1,R);

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

if($flag == '0'){
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

if($flag == '0'){
	$total_amount = $GLOBALS["gross_total"] + $addons_array['pf']['value'] + $addons_array['freight']['value'] + $sgst + $cgst + $addons_array['roundoff'];
}else{
	$total_amount = $GLOBALS["gross_total"] + $addons_array['pf']['value'] + $addons_array['freight']['value'] + $igst + $addons_array['roundoff'];
}

$pdf->Cell(23,7,money_format('%!i', $total_amount),'LB',1,R);

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
		$pdf->Cell(15,5,money_format('%!i', $tax_details['taxable'][$i]),'',0,C);
		$pdf->Cell(15,5,money_format('%!i', $tax_details['cgst'][$i]),'',0,C);
		$pdf->Cell(15,5,money_format('%!i', $tax_details['sgst'][$i]),'',0,C);
		$pdf->Cell(20,5,money_format('%!i', $tax_details['total'][$i]),'',1,C);

		$tot_taxable += $tax_details['taxable'][$i];
		$tot_cgst += $tax_details['cgst'][$i];
		$tot_sgst += $tax_details['sgst'][$i];
		$tot_total += $tax_details['total'][$i];
	}

	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(15,5,'Totals','TB',0,C);
	$pdf->Cell(15,5,'','TB',0,C);
	$pdf->Cell(15,5,money_format('%!i', $tot_taxable),'TB',0,C);
	$pdf->Cell(15,5,money_format('%!i', $tot_cgst),'TB',0,C);
	$pdf->Cell(15,5,money_format('%!i', $tot_sgst),'TB',0,C);
	$pdf->Cell(20,5,money_format('%!i', $tot_total),'TB',1,C);
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
		$pdf->Cell(15,5,money_format('%!i', $tax_details['taxable'][$i]),'',0,C);
		$pdf->Cell(15,5,money_format('%!i', $tax_details['igst'][$i]),'',0,C);
		$pdf->Cell(20,5,money_format('%!i', $tax_details['total'][$i]),'',1,C);

		$tot_taxable += $tax_details['taxable'][$i];
		$tot_igst += $tax_details['igst'][$i];
		$tot_total += $tax_details['total'][$i];
	}

	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(15,5,'Totals','TB',0,C);
	$pdf->Cell(15,5,'','TB',0,C);
	$pdf->Cell(15,5,money_format('%!i', $tot_taxable),'TB',0,C);
	$pdf->Cell(15,5,money_format('%!i', $tot_igst),'TB',0,C);
	$pdf->Cell(20,5,money_format('%!i', $tot_total),'TB',1,C);
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
	$pdf->Cell(95,5,'for AMMAR INDUSTRIAL CORPORATION',0,1,R);
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

$sql_website = "SELECT * FROM email_settings";
$query_website = $db->query($sql_website);
$row_website = $query_website->fetch_assoc();

$sending_host = $row_website['sending_host'];
$sending_email = $row_website['sending_email'];
$sending_email_password = $row_website['sending_email_password'];

$filename = "Purchase_Order_AICPO-".substr($GLOBALS["po_no"],7,4)."_".str_replace('-','',$GLOBALS["dt"]).".pdf";

$attachment= $pdf->output($filename, 'S');

use PHPMailer\PHPMailer\PHPMailer;
require '../plugins/custom/PHPMailer/src/PHPMailer.php';
require '../plugins/custom/PHPMailer/src/SMTP.php';
require '../plugins/custom/PHPMailer/src/Exception.php';

$email_arr = trim($_REQUEST['po_em_email']);
$email_arr = str_replace(" ","",$email_arr);

$email = explode(',', $email_arr);
$len = sizeof($email);
$cc = explode(',', $_REQUEST['po_em_email_cc']);
$len_cc = sizeof($cc);
$bcc = explode(',', $_REQUEST['po_em_email_bcc']);
$len_bcc = sizeof($bcc);

$subject = $_REQUEST['po_em_subject'];
$message = $_REQUEST['po_em_message'];

$validator = array('success' => false, 'message' => 'Message could not be sent. Mailer Error: {$mail->ErrorInfo}');

//PHPMailer Object
$mail = new PHPMailer(true); //Argument true in constructor enables exceptions

// $mail->isSMTP();
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
}

//Address to which recipient will reply
$mail->addReplyTo("info@ammarindustrial.in");

//CC and BCC
if($_REQUEST['po_em_email_cc'] != ''){
    for($k=0;$k<$len_cc;$k++){
        $mail->addCC($cc[$k], '');     // Add a recipient
    }
}
if($_REQUEST['po_em_email_bcc'] != ''){
    for($k=0;$k<$len_bcc;$k++){
        $mail->addBCC($bcc[$k], '');     // Add a recipient
    }
}

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

echo json_encode($validator);

function save_mail($mail)
{
    //You can change 'Sent Mail' to any other folder or tag
    $path = "{ammarindustrial.in:993/imap/ssl/novalidate-cert}INBOX.Sent";
    //Tell your server to open an IMAP connection using the same username and password as you used for SMTP
    $imapStream = imap_open($path, $mail->Username, $mail->Password);
    $result = imap_append($imapStream, $path, $mail->getSentMIMEMessage());
    imap_close($imapStream);
    return $result;
}
?>
