<?php
//ini_set("display_errors",1);
require('pdf_js.php');
include ("connect.php");
session_start();
setlocale(LC_MONETARY, 'en_IN');

$formatter = new NumberFormatter('en_IN', NumberFormatter::CURRENCY);
// Set a custom pattern to exclude the currency symbol
$formatter->setPattern('¤#,##0.00'); // Original pattern
$pattern = str_replace('¤', '', $formatter->getPattern()); // Remove the currency symbol placeholder
$formatter->setPattern($pattern);

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

		// $this->SetFont('Arial','B',12);
		// $this->Cell(74,5,"Estimate / Quotation",0,1,'C');
        // $this-> Ln(2);
        // $this->SetFont('Arial','',6);
        // $temp = "Date : ".$GLOBALS["dt"];
		// $this->CellFitScale(74,4,$temp,0,1,'R');
        // $this-> Ln(2);
		// $this->SetFont('Arial','',6);
		// $temp = " Name. :   ".$GLOBALS["client"];
		// $this->CellFitScale(10,3,"Name : ",0,0,'L');
        // $this->CellFitScale(67,3,$GLOBALS["client"],0,1,'L');
        // $this->SetFont('Arial','I',5);
        // $tmp = $GLOBALS["add1"];
        // $this->Cell(10, 3, '', 0, 0);
		// $this->CellFitScale(67,3,$tmp,0,1,"L");
		// $this->Cell(10, 3, '', 0, 0);
        // $tmp = $GLOBALS["city"].' - '.$GLOBALS["pincode"].', '.$GLOBALS["state"];
		// $this->CellFitScale(67,3,$tmp,0,1,L);
		// $this->SetFont('Arial','',6);
		// $this->CellFitScale(10,4,"No. :",0,0,'L');
		// $this->CellFitScale(67,4,$GLOBALS["si_no"],0,1,'L');

		
		
	}

	// Table with item details
    function InvoiceTable($header, $data) {
        // Column widths
        $w = array(3, 37, 13, 7, 16);
        // Header
        for ($i = 0; $i < count($header); $i++) {
            $this->CellFitScale($w[$i], 4, $header[$i], 1, 0, 'C');
        }
        $this->Ln();
    
        // Data
        foreach ($data as $row) {
            // Calculate the maximum height of the row
            $nb = 0;
            for ($i = 0; $i < count($row); $i++) {
                $nb = max($nb, $this->NbLines($w[$i], $row[$i]));
            }
            $h = 4 * $nb;
    
            // Issue a page break first if needed
            $this->CheckPageBreak($h);


    
            // Draw the cells of the row
            for ($i = 0; $i < count($row); $i++) {

                if($i == 1){
                    $align = 'L';
                }else if($i == 0){
                    $align = 'C';
                }else{
                    $align = 'R';
                }
                $x = $this->GetX();
                $y = $this->GetY();
                $this->Rect($x, $y, $w[$i], $h);
                $this->MultiCell($w[$i], 4, $row[$i], 0, $align);
                $this->SetXY($x + $w[$i], $y);
            }
            $this->Ln($h);
        }
        // Closing line
        $this->Cell(array_sum($w), 0, '', 'T');
    }
    
    

    
    function NbLines($w, $txt) {
        // Compute the number of lines a MultiCell of width w will take
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0) {
            $w = $this->w - $this->rMargin - $this->x;
        }
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 && $s[$nb - 1] == "\n") {
            $nb--;
        }
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ') {
                $sep = $i;
            }
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j) {
                        $i++;
                    }
                } else {
                    $i = $sep + 1;
                }
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else {
                $i++;
            }
        }
        return $nl;
    }
    
    function CheckPageBreak($h) {
        // If the height h would cause an overflow, add a new page immediately
        if ($this->GetY() + $h > $this->PageBreakTrigger) {
            $this->AddPage($this->CurOrientation);
        }
    }
    

	// Page footer
	function Footer()
	{
	    $this->SetY(-1);
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

$sql = "SELECT * FROM sales_invoice WHERE `si_no` = '$si_no'";
$query = $db->query($sql);
if (!$query || !($row = $query->fetch_assoc())) {
	die('Record not found');
}

$client = $row['client_name'] ?? '';
$items = json_decode($row['items'] ?? '', true);
if (!is_array($items)) { $items = []; }

$sql_temp = "SELECT * FROM clients WHERE name = '$client'";
$query_temp = $db->query($sql_temp);
$row_temp = $query_temp ? $query_temp->fetch_assoc() : null;

$address = [];
if ($row_temp) {
	$address = json_decode($row_temp['address'] ?? '', true);
}
if (!is_array($address)) { $address = []; }
$contacts = [];
if ($row_temp) {
	$contacts = json_decode($row_temp['contacts'] ?? '', true);
}
if (!is_array($contacts)) { $contacts = []; }

$mobile=$row['mobile'] ?? '';
       
$GLOBALS["gross_total"] = '0';
$GLOBALS["si_no"] = $si_no;
$GLOBALS["dt"] = !empty($row['si_date']) ? date('d-m-Y', strtotime($row['si_date'])) : '';
$GLOBALS['add1'] = $address["address_1"] ?? '';
$GLOBALS['add2'] = $address["address_2"] ?? '';
$GLOBALS['city'] = $address["city"] ?? '';
$GLOBALS['pincode'] = $address["pincode"] ?? '';
$GLOBALS['state'] = $row_temp["state"] ?? '';

$GLOBALS['client'] = $row_temp['print_name'] ?? '';
$GLOBALS['totall'] = $row['total'] ?? 0;

$flag = 1;

if(($row_temp["state"] ?? '') == 'WEST BENGAL'){
	$flag = 0;
}

$pdf = new PDF_AutoPrint('P','mm',array(80,210)); // 80 mm width for 3" printer
$pdf->SetMargins(2,5,2); // Set left, top, and right margins to 3 mm
$pdf->AddPage();
$pdf->SetFont('Arial','',5);

$pdf->SetFont('Arial','B',15);
		$pdf->Cell(74,5,"Estimate / Quotation",0,1,'C');
        $pdf-> Ln(4);
        $pdf->SetFont('Arial','',8);
        $pdf->CellFitScale(10,4,"No. :",0,0,'L');
		$pdf->CellFitScale(30,4,$GLOBALS["si_no"],0,0,'L');
        $temp = "Date : ".$GLOBALS["dt"];
		$pdf->CellFitScale(34,4,$temp,0,1,'R');
        $pdf-> Ln(2);
		$pdf->SetFont('Arial','',9);
		$temp = " Name. :   ".$GLOBALS["client"];
		$pdf->CellFitScale(10,5,"Name : ",0,0,'L');
        $pdf->CellFitScale(67,5,$GLOBALS["client"],0,1,'L');
        $pdf->SetFont('Arial','',9);
        $tmp = $GLOBALS["add1"];
        $pdf->Cell(10, 3, '', 0, 0);
		$pdf->CellFitScale(67,3,$tmp,0,1,"L");
		$pdf->Cell(10, 3, '', 0, 0);
        $tmp = $GLOBALS["city"]."-".$GLOBALS["pincode"]." ".$GLOBALS["state"];
		$pdf->CellFitScale(67,3,$tmp,0,1,L);
		$pdf->SetFont('Arial','',8);
        $pdf-> Ln(3);
		$pdf->CellFitScale(10,4,"Mobile :",0,0,'L');
		$pdf->CellFitScale(67,4,$mobile,0,1,'L');
        $pdf-> Ln(3);


// $pdf->AliasNbPages();
// $pdf->AddPage();

//----------------------------------------------- Table Header -----------------------------------------------
$y = $pdf->getY();
$count = 1;
$total = 0;

$header = array('Sn', 'Item', 'Unit Price', 'Qty', 'Total');
$data = array();

$l = is_array($items['product'] ?? null) ? sizeof($items['product']) : 0;

//Printing All Items
for($i=0;$i<$l;$i++){
	$line_item = array();

	$line_item[] = $count++;
	$line_item[] = $items['product'][$i].''.$items['long_desc'][$i];
	$line_item[] = $formatter->formatCurrency($items['price'][$i], 'INR');
	$line_item[] = $items['quantity'][$i];
	$line_item[] = $formatter->formatCurrency($items['price'][$i] * $items['quantity'][$i], 'INR');
    $total += $items['price'][$i] * $items['quantity'][$i];
	$data[] = $line_item;
}
$pdf->InvoiceTable($header, $data);



$w = array(3,37, 13, 7,16);

$pdf->setX(2);
$pdf->SetFont('Arial','B',8);
$pdf->CellFitScale($w[0],4,'','1',0);
$pdf->CellFitScale($w[1],4,'TOTAL','1',0,'R');
$pdf->CellFitScale($w[2],4,'','1',0,'R');
$pdf->CellFitScale($w[3],4,'','1',0,'R');
$pdf->CellFitScale($w[4],4,$formatter->formatCurrency($GLOBALS['totall'], 'INR'),'1',1,'R');

$pdf->SetFont('Arial','I',8);
$pdf->Ln(3);
$amntinwrds=numberToWords($GLOBALS['totall']);
$temp = "Amount In Words : ".preg_replace('/\s+/', ' ', trim($amntinwrds));
$pdf->CellFitScale(74,4,$temp,0,3,'L');
$pdf->Ln(16);
$pdf->SetFont('Arial', 'B',8 );
$pdf->CellFitScale(20, 3, "Signature", 'T', 0, 'C');
$pdf->CellFitScale(24, 3, "", 0, 0, 'C');
$pdf->CellFitScale(30, 3, "Receiver's Signature", 'T', 1, 'C');
$pdf->SetFont('Arial','I',9);
$pdf->Ln(6);
$temp = "Thank You for your Business";
$pdf->CellFitScale(74,6,$temp,0,1,'C');
$pdf->SetFont('Arial', '',7 );
$pdf->CellFitScale(74,4,'We value simplicity that\'s why we\'re cash-only.',0,1,'C');
$pdf->CellFitScale(74,4,'It helps us keep prices fair, service fast, and quality high.',0,1,'C');
$pdf->CellFitScale(74,4,'Just great products, at great value - paid with cash.',0,1,'C');





$name = "S-".substr($GLOBALS["si_no"],7,4)."_".str_replace('-','',$GLOBALS["dt"]).".pdf";

if($pdf_type == 'print'){
	// $pdf->AutoPrint();
	$pdf->output('I',$name);
}else{
	$pdf->output('D',$name);
}




// ---------------------------------------------Number to words Function---------------------------------------------------
function numberToWords($number) {
    $no = round($number);
    $decimal = round($number - ($no = floor($number)), 2) * 100;
    $digits_length = strlen($no);
    $i = 0;
    $str = array();
    $words = array(
        0 => '', 1 => 'One', 2 => 'Two',
        3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
        7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
        10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
        13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
        16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
        19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
        40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
        70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety'
    );
    $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
    while ($i < $digits_length) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += ($divider == 10) ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number] .
                " " . $digits[$counter] . $plural . " " . $hundred :
                $words[floor($number / 10) * 10]
                . " " . $words[$number % 10] . " "
                . $digits[$counter] . $plural . " " . $hundred;
        } else $str[] = null;
    }
    $Rupees = implode('', array_reverse($str));
    $paise = ($decimal) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
    return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
}


?>
