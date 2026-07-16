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
		$this->Cell(190,7,'Enquiry',0,2,C);

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
		$this->Cell(90,3,'','R',2,L);
		$this->Cell(90,5,'Customer Details :','R',2,L);
		$this->SetFont('Arial','',9);
		$this->CellFitScale(90,5,$GLOBALS["client"],'R',2,L);
		$this->CellFitScale(90,5,$GLOBALS["add1"],'R',2,L);
		$tmp = $GLOBALS["add2"];
		$this->CellFitScale(90,5,$tmp,'R',2,L);
		$tmp = $GLOBALS["city"].' - '.$GLOBALS["pincode"].', '.$GLOBALS["state"].', '.$GLOBALS["country"];
		$this->CellFitScale(90,5,$tmp,'R',2,L);
		$temp = 'GSTIN / UIN : '.$GLOBALS["gstin"];
		$this->Cell(90,5,$temp,'R',2,L);
		$this->Cell(90,3,'','RB',2,L);

		$this->setXY('102',$y);

		$this->Cell(100,3,'',0,2,L);
		$this->CellFitScale(25,5,'Enquiry No#',0,0,L);
		$temp = " : ".$GLOBALS["enquiry_no"];
		$this->CellFitScale(75,5,$temp,0,1,L);
		$this->setX('102');
		$this->CellFitScale(25,5,'Enquiry Date',0,0,L);
		$temp = " : ".$GLOBALS["enquiry_date"];
		$this->CellFitScale(75,5,$temp,0,1,L);
		$this->setX('102');
		$this->CellFitScale(25,5,'Client Enquiry No#',0,0,L);
		$temp = " : ".$GLOBALS["cl_enquiry_no"];
		$this->CellFitScale(75,5,$temp,0,1,L);
		$this->setX('102');
		$this->CellFitScale(25,5,'Mode',0,0,L);
		$temp = " : ".$GLOBALS["mode"];
		$this->CellFitScale(75,5,$temp,0,1,L);
		$this->setX('102');
		$this->Cell(25,5,'',0,0,L);
		$this->Cell(75,5,'',0,1,L);
		$this->setX('102');
		$this->Cell(25,5,'',0,0,L);
		$this->Cell(75,5,'',0,1,L);
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

//--------------------------------------------- Define Variables & Fetch Data from Database --------------------------------------

$e_no = $_REQUEST['id'] ?? '';
$pdf_type = $_REQUEST['type'] ?? '';

$sql = "SELECT * FROM enquiry WHERE `enquiry_no` = '$e_no'";
$query = $db->query($sql);
$row = $query ? $query->fetch_assoc() : null;
if (!$row) {
	exit('Record not found');
}

$client = $row['client'] ?? '';
$items = json_decode($row['items'] ?? '', true);
if (!is_array($items)) {
	$items = [];
}

$sql_temp = "SELECT * FROM clients WHERE name = '$client'";
$query_temp = $db->query($sql_temp);
$row_temp = $query_temp ? $query_temp->fetch_assoc() : null;
if (!$row_temp) {
	exit('Record not found');
}

$address = json_decode($row_temp['address'] ?? '', true);
if (!is_array($address)) {
	$address = [];
}

$GLOBALS['client'] = $row_temp['print_name'] ?? '';
$GLOBALS['add1'] = $address['address_1'] ?? '';
$GLOBALS['add2'] = $address['address_2'] ?? '';
$GLOBALS['city'] = $address['city'] ?? '';
$GLOBALS['pincode'] = $address['pincode'] ?? '';
$GLOBALS['state'] = $row_temp['state'] ?? '';
$GLOBALS['country'] = $row_temp['country'] ?? '';
$GLOBALS['gstin'] = $row_temp['gstin'] ?? '';

$GLOBALS['enquiry_no'] = $row['enquiry_no'] ?? '';
$GLOBALS['enquiry_date'] = !empty($row['enquiry_date']) ? date('d-m-Y', strtotime($row['enquiry_date'])) : '';
$GLOBALS['cl_enquiry_no'] = $row['cl_enquiry_no'] ?? '';
$GLOBALS['mode'] = $row['mode'] ?? '';

$pdf = new PDF_AutoPrint();
$pdf->SetAutoPageBreak(true, 35);
$pdf->setMargins(10, 10);
$title = "Enquiry";
$pdf->SetTitle($title);

$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->setX('10');

//------------------------------------------------------ Table Header ---------------------------------------------------------------
$pdf->SetFont('Arial','B',7);
$pdf->Cell(7,6,'SN','RB',0,C);
$pdf->Cell(100,6,'DESCRIPTION OF GOODS','RB',0,C);
$pdf->Cell(33,6,'QUANTITY','RB',0,C);
$pdf->Cell(25,6,'STOCK IN HAND','RB',0,C);
$pdf->Cell(25,6,'STOCK IN CO','B',1,C);

$pdf->SetFont('Arial','',7);

$l = is_array($items['product'] ?? null) ? count($items['product']) : 0;

// Printing All Items
for($i=0;$i<$l;$i++){
	$pos = $i+1;

	$pr = $items['product'][$i];

	$sql_make = "SELECT * FROM product WHERE name = '$pr'";
	$query_make = $db->query($sql_make);
	$row_make = $query_make ? $query_make->fetch_assoc() : null;

	$temp = $items['desc'][$i];
	$product = dotcom_wordwrap($temp,75);
	$co = count($product);

	$temp = $items['product'][$i];

	$desc = dotcom_wordwrap($temp,75);
	$co_2 = count($desc);

	$description_array = explode('|', (string)($items['long_desc'][$i] ?? ''));
	$len = sizeof($description_array);

	$limit = $co * 5 + $co_2 * 5 + $len * 3;

	$tmp_y = $pdf->getY();
	$product_limit = 297 - 35 - $limit;
	if($tmp_y > $product_limit){
		$pdf->AddPage();

		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(7,6,'SN','RB',0,C);
		$pdf->Cell(100,6,'DESCRIPTION OF GOODS','RB',0,C);
		$pdf->Cell(33,6,'QUANTITY','RB',0,C);
		$pdf->Cell(25,6,'STOCK IN HAND','RB',0,C);
		$pdf->Cell(25,6,'STOCK IN CO','B',1,C);

	}

	// Printing Name of the Product
	$pdf->Cell(7,5,$pos,'R',0,C);
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(100,5,$product[0],'R',0,L);
	$pdf->SetFont('Arial','',8);
	$pdf->CellFitScale(33,5,$items['quantity'][$i],'R',0,C);
	$pdf->CellFitScale(25,5,$items['stock'][$i],'R',0,C);
	$pdf->CellFitScale(25,5,$items['co_stock'][$i],0,1,C);

	if($co > 1){
		for( $z=1 ; $z<$co ; $z++){
			$pdf->Cell(7,5,'','R',0,C);
			$pdf->SetFont('Arial','B',7);
			$pdf->Cell(100,5,$product[$z],'R',0,L);
			$pdf->SetFont('Arial','',7);
			$pdf->Cell(33,5,'','R',0,C);
			$pdf->Cell(25,5,'','R',0,C);
			$pdf->Cell(25,5,'',0,1,C);
			
		}
	}

	// Printing SKU & Make
	for( $z=0 ; $z<$co_2 ; $z++){
		$pdf->Cell(7,5,'','R',0,C);
		$pdf->SetFont('Arial','I',7);
		$pdf->Cell(100,5,$desc[$z],'R',0,L);
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(33,5,'','R',0,C);
		$pdf->Cell(25,5,'','R',0,C);
		$pdf->Cell(25,5,'',0,1,C);
		
	}

	// Printing Description
	for($k=0;$k<$len;$k++){
		$pdf->Cell(7,3,'','R',0,C);
		$pdf->SetFont('Arial','I',7);
		$temp = '     '.$description_array[$k];
		$pdf->Cell(100,3,$temp,'R',0,L);
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(33,3,'','R',0,C);
		$pdf->Cell(25,3,'','R',0,C);
		$pdf->Cell(25,3,'',0,1,C);
	}


}

$pdf->Cell(190,3,'','T',0,C);

// $name = "Enquiry_AICE-".substr($GLOBALS["enquiry_no"]),6,4)."_".str_replace('-','',$GLOBALS["enquiry_date"]).".pdf";
$name = "Enquiry.pdf";
// $pdf->AutoPrint();
// Quotation_AICQ-0006_06042020
if($pdf_type == 'print'){
	$pdf->AutoPrint();
	$pdf->output('I',$name);
}else{
	$pdf->output('D',$name);
}

?>
