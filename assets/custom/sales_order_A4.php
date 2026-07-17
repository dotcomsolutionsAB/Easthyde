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
		$this->Rect(8, 8, 132, 194, 'D');
		$this->Rect(156, 8, 132, 194, 'D');

		$this->SetFont('Arial','B',12);
		$this->Cell(128,3,'',0,2,C);
		$this->Cell(128,5,$GLOBALS["client"],0,0,C);
		$this->Cell(20,5,'',0,0,C);
		$this->Cell(128,5,$GLOBALS["client"],0,1,C);
		$this->SetFont('Arial','',10);
		$this->Cell(128,5,"Delivery Note / Approval",0,0,C);
		$this->Cell(20,5,"",0,0,C);
		$this->Cell(128,5,"Delivery Note / Approval",0,1,C);
		$this->Cell(128,3,"",0,2,C);

		$this->SetFont('Arial','',9);
		$this->Cell(5,5,"",0,0,C);
		$temp = "No. : ".$GLOBALS["so_no"];
		$this->Cell(59,5,$temp,0,0,L);
		$temp = "Date : ".$GLOBALS["dt"];
		$this->Cell(59,5,$temp,0,0,R);
		$this->Cell(25,5,'',0,0,R);

		$this->Cell(5,5,"",0,0,C);
		$temp = "No. : ".$GLOBALS["so_no"];
		$this->Cell(59,5,$temp,0,0,L);
		$temp = "Date : ".$GLOBALS["dt"];
		$this->Cell(59,5,$temp,0,1,R);

		$this->Cell(128,3,"",0,2,C);


		
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
	    $this->Cell(128,20,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	    $this->Cell(20,20,'',0,0,'C');
	    $this->Cell(128,20,'Page '.$this->PageNo().'/{nb}',0,0,'C');
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

$so_no = $_REQUEST['id'];
$pdf_type = $_REQUEST['type'];

$sql = "SELECT * FROM sales_order WHERE `so_no` = '$so_no'";
$query = $db->query($sql);
$row = $query->fetch_assoc();

$client = $row['client_name'];
$items = json_decode($row['items'], true);

$GLOBALS["client_so_no"] = $row['client_so_no'];

$sql_temp = "SELECT * FROM clients WHERE name = '$client'";
$query_temp = $db->query($sql_temp);
$row_temp = $query_temp->fetch_assoc();

$address = json_decode($row_temp['address'], true);

$GLOBALS["gross_total"] = '0';
$GLOBALS["so_no"] = $so_no;
$GLOBALS["dt"] = date('d-m-Y', strtotime($row['so_date']));

$GLOBALS['client'] = $row_temp['print_name'];

$flag = 1;

if($row_temp["state"] == 'WEST BENGAL'){
	$flag = 0;
}

$pdf = new PDF_AutoPrint('L','mm',array(210,297));
$pdf->SetAutoPageBreak(true, 10);
$pdf->setMargins(10, 10);
$title = "Local Slip";
$pdf->SetTitle($title);

$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->setX('10');

//----------------------------------------------- Table Header -----------------------------------------------
$y = $pdf->getY();

$pdf->SetFont('Arial','B',7);
$pdf->Cell(7,4,'SN','TR',0,C);
$pdf->Cell(63,4,'DESCRIPTION OF GOODS','TR',0,C);
$pdf->Cell(10,4,'QTY','TR',0,C);
$pdf->Cell(17,4,'RATE','TR',0,C);
$pdf->Cell(10,4,'DISC%','TR',0,C);
$pdf->Cell(21,4,'AMOUNT','T',0,C);

$pdf->Cell(20,4,'','',0,C);

$pdf->Cell(7,4,'SN','TR',0,C);
$pdf->Cell(63,4,'DESCRIPTION OF GOODS','TR',0,C);
$pdf->Cell(10,4,'QTY','TR',0,C);
$pdf->Cell(17,4,'RATE','TR',0,C);
$pdf->Cell(10,4,'DISC%','TR',0,C);
$pdf->Cell(21,4,'AMOUNT','T',1,C);

$pdf->Cell(7,4,'','BR',0,C);
$pdf->Cell(63,4,'','BR',0,C);
$pdf->Cell(10,4,'','BR',0,C);
$pdf->Cell(17,4,'','BR',0,C);
$pdf->Cell(10,4,'','BR',0,C);
$pdf->Cell(21,4,'(Rs)','B',0,C);

$pdf->Cell(20,4,'','',0,C);

$pdf->Cell(7,4,'','BR',0,C);
$pdf->Cell(63,4,'','BR',0,C);
$pdf->Cell(10,4,'','BR',0,C);
$pdf->Cell(17,4,'','BR',0,C);
$pdf->Cell(10,4,'','BR',0,C);
$pdf->Cell(21,4,'(Rs)','B',1,C);

$pdf->SetFont('Arial','',7);
$grand_total_qty = 0;

$tax_details = array('hsn'=>array(), 'rate'=>array(), 'taxable'=>array(), 'cgst'=>array(), 'sgst'=>array(), 'igst'=>array(), 'total'=>array());

$l = sizeof($items['product']);

//Printing All Items
for($i=0;$i<$l;$i++){
	$pos = $i+1;

	$tax = (float)($items['tax'][$i] ?? 0)/2;
	$tax_amount = $items['tax_amount'][$i] / 2;
	$pr = $items['product'][$i];
	$make = $items['group'][$i];

	$line_total = (float)($items['quantity'][$i] ?? 0)*(float)($items['price'][$i] ?? 0)*(100-(float)($items['discount'][$i] ?? 0))/100;
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

		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(7,4,'SN','R',0,C);
		$pdf->Cell(63,4,'DESCRIPTION OF GOODS','R',0,C);
		$pdf->Cell(10,4,'QTY','R',0,C);
		$pdf->Cell(17,4,'RATE','R',0,C);
		$pdf->Cell(10,4,'DISC%','R',0,C);
		$pdf->Cell(21,4,'AMOUNT',0,0,C);

		$pdf->Cell(20,4,'',0,0,C);

		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(7,4,'SN','R',0,C);
		$pdf->Cell(63,4,'DESCRIPTION OF GOODS','R',0,C);
		$pdf->Cell(10,4,'QTY','R',0,C);
		$pdf->Cell(17,4,'RATE','R',0,C);
		$pdf->Cell(10,4,'DISC%','R',0,C);
		$pdf->Cell(21,4,'AMOUNT',0,1,C);

		$pdf->Cell(7,4,'','BR',0,C);
		$pdf->Cell(63,4,'','BR',0,C);
		$pdf->Cell(10,4,'','BR',0,C);
		$pdf->Cell(17,4,'','BR',0,C);
		$pdf->Cell(10,4,'','BR',0,C);
		$pdf->Cell(21,4,'(Rs)','B',1,C);

		$pdf->Cell(20,4,'','',1,C);

		$pdf->Cell(7,4,'','BR',0,C);
		$pdf->Cell(63,4,'','BR',0,C);
		$pdf->Cell(10,4,'','BR',0,C);
		$pdf->Cell(17,4,'','BR',0,C);
		$pdf->Cell(10,4,'','BR',0,C);
		$pdf->Cell(21,4,'(Rs)','B',1,C);

	}

	// Printing Name of the Product
	$pdf->Cell(7,5,$pos,'R',0,C);
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(63,5,$product[0],'R',0,L);
	$pdf->SetFont('Arial','',8);
	$pdf->CellFitScale(10,5,$items['quantity'][$i],'R',0,C);
	if($items['price'][$i] > 0)
	{
		$pdf->CellFitScale(17,5,money_format('%!i', $items['price'][$i]),'R',0,R);
		if($items['discount'][$i] != '')
			$pdf->CellFitScale(10,5,money_format('%!i', $items['discount'][$i]),'R',0,C);
		else
			$pdf->CellFitScale(10,5,money_format('%!i', '0'),'R',0,C);
	}else{
		$pdf->CellFitScale(17,5,'','R',0,R);
		$pdf->CellFitScale(10,5,'','R',0,R);
	}
	$pdf->CellFitScale(21,5,money_format('%!i', $line_total),'',0,R);

	$pdf->CellFitScale(20,5,'','',0,R);


	$pdf->Cell(7,5,$pos,'R',0,C);
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(63,5,$product[0],'R',0,L);
	$pdf->SetFont('Arial','',8);
	$pdf->CellFitScale(10,5,$items['quantity'][$i],'R',0,C);
	if($items['price'][$i] > 0)
	{
		$pdf->CellFitScale(17,5,money_format('%!i', $items['price'][$i]),'R',0,R);
		if($items['discount'][$i] != '')
			$pdf->CellFitScale(10,5,money_format('%!i', $items['discount'][$i]),'R',0,C);
		else
			$pdf->CellFitScale(10,5,money_format('%!i', '0'),'R',0,C);
	}else{
		$pdf->CellFitScale(17,5,'','R',0,R);
		$pdf->CellFitScale(10,5,'','R',0,R);
	}
	$pdf->CellFitScale(21,5,money_format('%!i', $line_total),'',1,R);	


	if($co > 1){
		for( $z=1 ; $z<$co ; $z++){
			$pdf->Cell(7,5,'','R',0,C);
			$pdf->SetFont('Arial','B',7);
			$pdf->Cell(63,5,$product[$z],'R',0,L);
			$pdf->SetFont('Arial','',7);
			$pdf->Cell(10,5,'','R',0,C);
			$pdf->Cell(17,5,'','R',0,R);
			$pdf->Cell(10,5,'','R',0,C);
			$pdf->Cell(21,5,'','',0,R);

			$pdf->Cell(20,5,'','',0,R);

			$pdf->Cell(7,5,'','R',0,C);
			$pdf->SetFont('Arial','B',7);
			$pdf->Cell(63,5,$product[$z],'R',0,L);
			$pdf->SetFont('Arial','',7);
			$pdf->Cell(10,5,'','R',0,C);
			$pdf->Cell(17,5,'','R',0,R);
			$pdf->Cell(10,5,'','R',0,C);
			$pdf->Cell(21,5,'','',1,R);
			
		}
	}

	// Printing SKU & Make
	for( $z=0 ; $z<$co_2 ; $z++){
		$pdf->Cell(7,5,'','R',0,C);
		$pdf->SetFont('Arial','I',7);
		$pdf->Cell(63,5,$desc[$z],'R',0,L);
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(10,5,'','R',0,C);
		$pdf->Cell(17,5,'','R',0,R);
		$pdf->Cell(10,5,'','R',0,C);
		$pdf->Cell(21,5,'','',0,R);

		$pdf->Cell(20,5,'','',0,R);

		$pdf->Cell(7,5,'','R',0,C);
		$pdf->SetFont('Arial','I',7);
		$pdf->Cell(63,5,$desc[$z],'R',0,L);
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(10,5,'','R',0,C);
		$pdf->Cell(17,5,'','R',0,R);
		$pdf->Cell(10,5,'','R',0,C);
		$pdf->Cell(21,5,'','',1,R);
		
	}

	// Printing Description
	for($k=0;$k<$len;$k++){
		$pdf->Cell(7,3,'','R',0,C);
		$pdf->SetFont('Arial','I',7);
		$temp = '     '.$description_array[$k];
		$pdf->Cell(63,3,$temp,'R',0,L);
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(10,3,'','R',0,C);
		$pdf->Cell(17,3,'','R',0,R);
		$pdf->Cell(10,3,'','R',0,C);
		$pdf->Cell(21,3,'','',0,R);

		$pdf->Cell(20,3,'','',0,R);

		$pdf->Cell(7,3,'','R',0,C);
		$pdf->SetFont('Arial','I',7);
		$temp = '     '.$description_array[$k];
		$pdf->Cell(63,3,$temp,'R',0,L);
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(10,3,'','R',0,C);
		$pdf->Cell(17,3,'','R',0,R);
		$pdf->Cell(10,3,'','R',0,C);
		$pdf->Cell(21,3,'','',1,R);
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
		$tax_details['taxable'][$pos] += $items['amount'][$i] - $items['tax_amount'][$i];
		$tax_details['cgst'][$pos] += $items['tax_amount'][$i] / 2;
		$tax_details['sgst'][$pos] += $items['tax_amount'][$i] / 2;
		$tax_details['igst'][$pos] += $items['tax_amount'][$i];
		$tax_details['total'][$pos] += $items['tax_amount'][$i];

	}else{
		$tax_details['hsn'][] = $items['hsn'][$i];
		$tax_details['rate'][] = (float)($items['tax'][$i] ?? 0);
		$tax_details['taxable'][] = $items['amount'][$i] - $items['tax_amount'][$i];
		$tax_details['cgst'][] = $items['tax_amount'][$i] / 2;
		$tax_details['sgst'][] = $items['tax_amount'][$i] / 2;
		$tax_details['igst'][] = $items['tax_amount'][$i];
		$tax_details['total'][] = $items['tax_amount'][$i];
	}

}

//Addons
$pdf->Cell(107,3,'','TR',0,C);
$pdf->Cell(21,3,'','T',0,C);

$pdf->Cell(20,3,'','',0,C);

$pdf->Cell(107,3,'','TR',0,C);
$pdf->Cell(21,3,'','T',1,C);

$t_tax = $items['tax'][0];
$t2_tax = $t_tax/2;

$addons_array = json_decode($row['addons'], true);

$pdf->Cell(70,5,'',0,0,L);
$pdf->SetFont('Arial','I',9);
$pdf->Cell(37,5,'Gross Total','R',0,L);
$pdf->SetFont('Arial','',9);
$pdf->Cell(21,5,money_format('%!i',$GLOBALS["gross_total"]),0,0,R);

$pdf->Cell(20,5,'',0,0,R);

$pdf->Cell(70,5,'',0,0,L);
$pdf->SetFont('Arial','I',9);
$pdf->Cell(37,5,'Gross Total','R',0,L);
$pdf->SetFont('Arial','',9);
$pdf->Cell(21,5,money_format('%!i',$GLOBALS["gross_total"]),0,1,R);

if($addons_array['pf']!='' && $addons_array['pf'] > 0){
	$pdf->Cell(70,5,'',0,0,L);
	$pdf->SetFont('Arial','I',9);
	$pdf->Cell(37,5,'Add   : Packaging & Forwarding','R',0,L);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(21,5,money_format('%!i',$addons_array['pf']),0,0,R);

	$pdf->Cell(20,5,'',0,0,R);

	$pdf->Cell(70,5,'',0,0,L);
	$pdf->SetFont('Arial','I',9);
	$pdf->Cell(37,5,'Add   : Packaging & Forwarding','R',0,L);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(21,5,money_format('%!i',$addons_array['pf']),0,1,R);

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
	$pdf->Cell(70,5,'',0,0,L);
	$pdf->SetFont('Arial','I',9);
	$pdf->Cell(37,5,'Add   : Freight','R',0,L);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(21,5,money_format('%!i',$addons_array['freight']),0,0,R);

	$pdf->Cell(20,5,'',0,0,R);

	$pdf->Cell(70,5,'',0,0,L);
	$pdf->SetFont('Arial','I',9);
	$pdf->Cell(37,5,'Add   : Freight','R',0,L);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(21,5,money_format('%!i',$addons_array['freight']),0,1,R);

	$hsn = '9968';
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
	$sgst += (float)($tax_details['sgst'][$j] ?? 0);
	$cgst += (float)($tax_details['cgst'][$j] ?? 0);
	$igst += (float)($tax_details['igst'][$j] ?? 0);
}

if($flag == '0'){
	$pdf->SetFont('Arial','I',9);
	$pdf->Cell(70,5,'',0,0,L);
	$pdf->Cell(37,5,'Add   : CGST','R',0,L);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(21,5,money_format('%!i', $cgst),0,0,R);

	$pdf->Cell(20,5,'',0,0,R);

	$pdf->SetFont('Arial','I',9);
	$pdf->Cell(70,5,'',0,0,L);
	$pdf->Cell(37,5,'Add   : CGST','R',0,L);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(21,5,money_format('%!i', $cgst),0,1,R);

	$pdf->Cell(70,5,'',0,0,L);
	$pdf->SetFont('Arial','I',9);
	$pdf->Cell(37,5,'Add   : SGST','R',0,L);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(21,5,money_format('%!i', $sgst),0,0,R);

	$pdf->Cell(20,5,'',0,0,R);

	$pdf->Cell(70,5,'',0,0,L);
	$pdf->SetFont('Arial','I',9);
	$pdf->Cell(37,5,'Add   : SGST','R',0,L);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(21,5,money_format('%!i', $sgst),0,1,R);
}else{
	$pdf->SetFont('Arial','I',9);
	$pdf->Cell(70,5,'',0,0,L);
	$pdf->Cell(37,5,'Add   : IGST','R',0,L);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(21,5,money_format('%!i', $igst),0,0,R);

	$pdf->Cell(20,5,'',0,0,R);

	$pdf->SetFont('Arial','I',9);
	$pdf->Cell(70,5,'',0,0,L);
	$pdf->Cell(37,5,'Add   : IGST','R',0,L);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(21,5,money_format('%!i', $igst),0,1,R);

}

if($addons_array['discount']!= '' && $addons_array['discount'] > 0)
{
	$pdf->Cell(70,5,'',0,0,L);
	$pdf->SetFont('Arial','I',9);
	$pdf->Cell(37,5,'Less   : Discount','R',0,L);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(21,5,money_format('%!i',$addons_array['discount']),0,0,R);

	$pdf->Cell(20,5,'',0,0,R);

	$pdf->Cell(70,5,'',0,0,L);
	$pdf->SetFont('Arial','I',9);
	$pdf->Cell(37,5,'Less   : Discount','R',0,L);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(21,5,money_format('%!i',$addons_array['discount']),0,1,R);
}

if($addons_array['roundoff']!='0' && $addons_array['roundoff'] != 0)
{
	if($addons_array['roundoff'] < 0){
		$roundoff_temp = (float)($addons_array['roundoff'] ?? 0) * -1;
		$pdf->Cell(70,5,'',0,0,L);
		$pdf->SetFont('Arial','I',9);
		$pdf->Cell(37,5,'Less : Rounded Off (-)','R',0,L);
		$pdf->SetFont('Arial','',9);
		$pdf->Cell(21,5,money_format('%!i',$roundoff_temp),0,0,R);

		$pdf->Cell(20,5,'',0,0,R);

		$pdf->Cell(70,5,'',0,0,L);
		$pdf->SetFont('Arial','I',9);
		$pdf->Cell(37,5,'Less : Rounded Off (-)','R',0,L);
		$pdf->SetFont('Arial','',9);
		$pdf->Cell(21,5,money_format('%!i',$roundoff_temp),0,1,R);
	}else{
		$pdf->Cell(70,5,'',0,0,L);
		$pdf->SetFont('Arial','I',9);
		$pdf->Cell(37,5,'Add : Rounded Off (+)','R',0,L);
		$pdf->SetFont('Arial','',9);
		$pdf->Cell(21,5,money_format('%!i',$addons_array['roundoff']),0,0,R);

		$pdf->Cell(20,5,'',0,0,R);

		$pdf->Cell(70,5,'',0,0,L);
		$pdf->SetFont('Arial','I',9);
		$pdf->Cell(37,5,'Add : Rounded Off (+)','R',0,L);
		$pdf->SetFont('Arial','',9);
		$pdf->Cell(21,5,money_format('%!i',$addons_array['roundoff']),0,1,R);
	}
}

$pdf->Cell(107,3,'','BR',0,C);
$pdf->Cell(21,3,'','B',0,C);

$pdf->Cell(20,3,'','',0,C);

$pdf->Cell(107,3,'','BR',0,C);
$pdf->Cell(21,3,'','B',1,C);
//End Addons

$pdf->SetFont('Arial','B',9);

$pdf->Cell(70,7,'',0,0,R);
$pdf->Cell(10,7,$grand_total_qty,'B',0,C);
$pdf->Cell(27,7,'GRAND TOTAL',0,0,R);

if($flag == '0'){
	$total_amount = $GLOBALS["gross_total"] + $addons_array['pf'] + $addons_array['freight'] + $sgst + $cgst - $addons_array['discount'] + $addons_array['roundoff'];
}else{
	$total_amount = $GLOBALS["gross_total"] + $addons_array['pf'] + $addons_array['freight'] + $igst - $addons_array['discount'] + $addons_array['roundoff'];
}

$pdf->Cell(21,7,money_format('%!i', $total_amount),'LB',0,R);

$pdf->Cell(20,7,'','',0,R);

$pdf->Cell(70,7,'',0,0,R);
$pdf->Cell(10,7,$grand_total_qty,'B',0,C);
$pdf->Cell(27,7,'GRAND TOTAL',0,0,R);

if($flag == '0'){
	$total_amount = $GLOBALS["gross_total"] + $addons_array['pf'] + $addons_array['freight'] + $sgst + $cgst - $addons_array['discount'] + $addons_array['roundoff'];
}else{
	$total_amount = $GLOBALS["gross_total"] + $addons_array['pf'] + $addons_array['freight'] + $igst - $addons_array['discount'] + $addons_array['roundoff'];
}

$pdf->Cell(21,7,money_format('%!i', $total_amount),'LB',1,R);

$pdf->SetFont('Arial','',8);

$pdf->Cell(128,10,convertToIndianCurrency($total_amount),0,0,C);
$pdf->Cell(20,10,'',0,0,C);
$pdf->Cell(128,10,convertToIndianCurrency($total_amount),0,1,C);

$tr_flag = 0;

$tmp_y = $pdf->getY();
$terms_limit = 210 - 20;
$pdf->setY($terms_limit);

$y = $pdf->getY();
$pdf->SetFont('Arial','B',9);
$pdf->Cell(45,1,'','B',0,L);
$pdf->Cell(103,6,'',0,0,L);
$pdf->Cell(45,1,'','B',1,L);

$pdf->Cell(45,6,'Customer Signature & Date',0,0,L);
$pdf->Cell(103,6,'',0,0,L);
$pdf->Cell(45,6,'Customer Signature & Date',0,1,L);

$name = "Local_AICSO-".substr($GLOBALS["so_no"],7,4)."_".str_replace('-','',$GLOBALS["dt"]).".pdf";

if($pdf_type == 'print'){
	// $pdf->AutoPrint();
	$pdf->output('I',$name);
}else{
	$pdf->output('D',$name);
}

?>
