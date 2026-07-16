<!-- <?php
// error_reporting(E_ALL); 
//ini_set('display_errors', 1);
// ini_set('display_errors', 1);

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
		$this->CellFitScale(90,5,$GLOBALS["client"],'R',2,L);
		$this->SetFont('Arial','',9);
		$this->CellFitScale(90,5,$GLOBALS["add1"],'R',2,L);
		$tmp = $GLOBALS["add2"];
		$this->CellFitScale(90,5,$tmp,'R',2,L);
		$tmp = $GLOBALS["city"].' - '.$GLOBALS["pincode"].', '.$GLOBALS["state"].', '.$GLOBALS["country"];
		$this->CellFitScale(90,5,$tmp,'R',2,L);
		$temp = 'GSTIN / UIN : '.$GLOBALS["gstin"];
		$this->Cell(90,5,$temp,'RB',2,L);
		$this->SetFont('Arial','B',9);
		$this->Cell(90,5,'Shipping Details :','R',2,L);
		$this->SetFont('Arial','B',9);
		$this->CellFitScale(90,5,$GLOBALS["ship_client"],'R',2,L);
		$this->SetFont('Arial','',9);
		$this->CellFitScale(90,5,$GLOBALS["ship_add1"],'R',2,L);
		$tmp = $GLOBALS["ship_add2"];
		$this->CellFitScale(90,5,$tmp,'R',2,L);
		$tmp = $GLOBALS["ship_city"].' - '.$GLOBALS["ship_pincode"].', '.$GLOBALS["ship_state"].', '.$GLOBALS["ship_country"];
		$this->CellFitScale(90,5,$tmp,'R',2,L);
		$this->CellFitScale(90,6,$GLOBALS['mobile'],'RB',2,L);
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
        //Get string width
        $str_width=$this->GetStringWidth($txt);

        if($str_width == 0 || $str_width == null)
        	$str_width = 1;

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

$si_no = $_REQUEST['id'];
$pdf_type = $_REQUEST['type'];

$start = 1;
$copies = 1;
if($_REQUEST['si_start'] != '')
	$start = $_REQUEST['si_start'];
if($_REQUEST['si_copies'] != '')
	$copies = $_REQUEST['si_copies'];

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
$GLOBALS["vendor"]='';
if($row_temp['vendor_code']!=null)
{
	$GLOBALS["vendor"] = "Vendor Code : ".$row_temp['vendor_code'];
}

$address = json_decode($row_temp['address'], true);

$GLOBALS["si_no"] = $si_no;
$GLOBALS["dt"] = date('d-m-Y', strtotime($row['si_date']));

$GLOBALS['client'] = $row_temp['print_name'];
$GLOBALS['add1'] = $address["address_1"];
$GLOBALS['add2'] = $address["address_2"];
$GLOBALS['city'] = $address["city"];
$GLOBALS['pincode'] = $address["pincode"];
$GLOBALS['state'] = $row_temp["state"];
$GLOBALS['country'] = $row_temp["country"];
if($row["mobile"]!=null){
$GLOBALS['mobile'] = "MOBILE No: ".$row["mobile"];
}

$GLOBALS['ship_client'] = $shipping['name'];
$GLOBALS['ship_add1'] 	= $shipping["address_1"];
$GLOBALS['ship_add2'] 	= $shipping["address_2"];
$GLOBALS['ship_city'] 	= $shipping["city"];
$GLOBALS['ship_pincode']= $shipping["pincode"];
$GLOBALS['ship_state'] 	= $row["state"];
$GLOBALS['ship_country']= $shipping["country"];

$GLOBALS['buyer_order'] = $invoice_details["buyer_order"];
if($invoice_details["order_date"] != '1970-01-01' && $invoice_details["order_date"] != '')
	$GLOBALS['order_date'] 		= date('d-m-Y', strtotime($invoice_details["order_date"]));
else
	$GLOBALS['order_date'] 		= '';
// $GLOBALS['order_date'] = date('d-m-Y', strtotime($invoice_details["order_date"]));
$GLOBALS['payment_terms'] = $invoice_details["payment_terms"];
$GLOBALS['other_ref'] = $invoice_details["other_ref"];
$GLOBALS['delivery_terms'] = $invoice_details["delivery_terms"];


$GLOBALS['despatch_medium'] 	= $invoice_details["despatch_medium"];
$GLOBALS['despatch_doc_no'] 	= $invoice_details["despatch_doc_no"];
if($invoice_details["despatch_date"] != '1970-01-01' && $invoice_details["despatch_date"] != '')
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

	$l = sizeof($items['product']);

	//Printing All Items
	for($i=0;$i<$l;$i++){
		$pos = $i+1;
		//$pdf->Cell(7,4,'HIiiii','R',0,C);
		if($state_flag == 0)
		{
			$tax = $items['tax'][$i]/2;
			$cgst = $items['cgst'][$i];
			$sgst = $items['sgst'][$i];
			$pr = $items['product'][$i];
			$make = $items['group'][$i];

			$line_total = $items['quantity'][$i]*$items['price'][$i]*(100-$items['discount'][$i])/100;
			$GLOBALS["gross_total"] += round($line_total,2);

			$sql_make = "SELECT * FROM product WHERE name = '$pr'";
			$query_make = $db->query($sql_make);
			$row_make = $query_make->fetch_assoc();
			$pr_group = strtoupper($row_make['group']);

			$temp = $items['desc'][$i];
			$product = dotcom_wordwrap($temp,50);
			$co = count($product);

			if($make == '1')
				$temp = $items['product'][$i].', Make : '.$pr_group;
			else
				$temp = $items['product'][$i];

			$desc = dotcom_wordwrap($temp,50);
			$co_2 = count($desc);

			$description_array = explode('|', $items['long_desc'][$i]);
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
			$tax = $items['tax'][$i];
			$igst = $items['igst'][$i];
			$pr = $items['product'][$i];
			$make = $items['group'][$i];

			$line_total = $items['quantity'][$i]*$items['price'][$i]*(100-$items['discount'][$i])/100;
			$GLOBALS["gross_total"] += round($line_total,2);

			$sql_make = "SELECT * FROM product WHERE name = '$pr'";
			$query_make = $db->query($sql_make);
			$row_make = $query_make->fetch_assoc();
			$pr_group = strtoupper($row_make['group']);

			$temp = $items['desc'][$i];
			$product = dotcom_wordwrap($temp,50);
			$co = count($product);

			if($make == '1')
				$temp = $items['product'][$i].', Make : '.$pr_group;
			else
				$temp = $items['product'][$i];

			$desc = dotcom_wordwrap($temp,50);
			$co_2 = count($desc);

			$description_array = explode('|', $items['long_desc'][$i]);
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
		$total_amount = $GLOBALS["gross_total"] + $addons_array['pf']['value'] + $addons_array['freight']['value'] + $sgst + $cgst + $addons_array['roundoff'];
	}else{
		$total_amount = $GLOBALS["gross_total"] + $addons_array['pf']['value'] + $addons_array['freight']['value'] + $igst + $addons_array['roundoff'];
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
		if($tmp_y > $hsn_limit){
			$GLOBALS["pages"]++;
			$pdf->AddPage();
		}
		

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
	$pdf->Cell(190,6,'BANK NAME : UCO BANK, BRANCH : CANNINNG STREET, A/C NO. : 13390200000481, IFSC :UCBA0001339','B',2,C);

	$y = $pdf->getY();
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(95,1,'','TR',0,L);
	$pdf->Cell(95,1,'','T',1,L);
	$pdf->Cell(95,5,'Customer\'s Signature:','R',0,L);
	$pdf->Cell(95,5,'for M.M. LUCKY ENTERPRISE',0,1,R);
	$pdf->Image("../media/company-logos/company_stamp.png",170,235,20,20);
	$pdf->Cell(95,12,'','R',0,L);
	$pdf->Cell(95,12,'','',1,R);
	$pdf->Cell(95,4,'','R',1,L);
	$pdf->Cell(95,4,'','R',0,L);
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

?> -->
