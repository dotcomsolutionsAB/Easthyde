<?php
require('pdf_js.php');
include ("connect.php");
session_start();
setlocale(LC_MONETARY, 'en_IN');

$formatter = new NumberFormatter('en_IN', NumberFormatter::CURRENCY);
$formatter->setPattern('¤#,##0.00');
$pattern = str_replace('¤', '', $formatter->getPattern());
$formatter->setPattern($pattern);

class PDF_AutoPrint extends PDF_JavaScript
{
    function AutoPrint($printer='')
    {
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

    function Header()
    {
        $this->SetDrawColor(0,0,0);
        $this->SetFont('Arial','B',16);
        $this->Cell(128,7,"Estimate / Quotation",0,1,'C');
        $this-> Ln(4);
        $this->SetFont('Arial','',10);
        $temp = "Date : ".$GLOBALS["dt"];
        $this->CellFitScale(128,6,$temp,0,1,'R');
        $this-> Ln(4);
        $this->SetFont('Arial','',10);
        $temp = $GLOBALS["client"];
        $this->CellFitScale(15,6,"Name : ",0,0,'L');
        $this->CellFitScale(113,6,$temp,0,1,'L');
        $this->SetFont('Arial','I',9);
        $tmp = $GLOBALS["add1"];
        $this->Cell(15, 6, '', 0, 0);
        $this->CellFitScale(113,5,$tmp,0,1,'L');
        $this->Cell(15, 6, '', 0, 0);
        $tmp = $GLOBALS["city"].' - '.$GLOBALS["pincode"].', '.$GLOBALS["state"];
        $this->CellFitScale(113,6,$tmp,0,1,'L');

        $this->SetFont('Arial','',10);
        $temp = $GLOBALS["si_no"];
        $this->CellFitScale(15,6,"No. :",0,0,'L');
        $this->CellFitScale(113,6,$temp,0,1,'L');

       
        $this->CellFitScale(15,6,"Mob :",0,0,'L');
		$this->CellFitScale(67,4,$GLOBALS['mobile'],0,1,'L');
        $this-> Ln(3);

    }

    function InvoiceTable($header, $data) {
        $w = array(10, 55, 18, 15, 30); // Adjust column widths to fit within the page width
        for ($i = 0; $i < count($header); $i++) {
            $this->CellFitScale($w[$i], 6, $header[$i], 1, 0, 'C');
        }
        $this->Ln();

        foreach ($data as $row) {
            $nb = 0;
            for ($i = 0; $i < count($row); $i++) {
                $nb = max($nb, $this->NbLines($w[$i], $row[$i]));
            }
            $h = 6 * $nb;

            $this->CheckPageBreak($h);

            for ($i = 0; $i < count($row); $i++) {
                $align = ($i == 1) ? 'L' : (($i == 0) ? 'C' : 'R');
                $x = $this->GetX();
                $y = $this->GetY();
                $this->Rect($x, $y, $w[$i], $h);
                $this->MultiCell($w[$i], 6, $row[$i], 0, $align);
                $this->SetXY($x + $w[$i], $y);
            }
            $this->Ln($h);
        }
        $this->Cell(array_sum($w), 0, '', 'T');
    }

    function NbLines($w, $txt) {
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
        if ($this->GetY() + $h > $this->PageBreakTrigger) {
            $this->AddPage($this->CurOrientation);
        }
    }

    function Footer()
    {
        $this->SetY(-30);
        $this->SetFont('Arial','I',9);
        $temp = "Thank You for your Business";
        $this->CellFitScale(128,6,$temp,0,1,'C');

        $this->Ln(4);

        $this->SetFont('Arial', 'B',9 );
        $this->CellFitScale(30, 6, "Signature", 'T', 0, 'C');
        $this->CellFitScale(68, 6, "", 0, 0, 'C');
        $this->CellFitScale(30, 6, "Receiver's Signature", 'T', 1, 'C');
    }

    function CellFit($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='', $scale=false, $force=true)
    {
        $txt = (string)($txt ?? '');
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
                $horiz_scale=$ratio*100.0;
                $this->_out(sprintf('BT %.2F Tz ET',$horiz_scale));
            }
            else
            {
                $char_space=($w-$this->cMargin*2-$str_width)/max(strlen($txt)-1,1)*$this->k;
                $this->_out(sprintf('BT %.2F Tc ET',$char_space));
            }
            $align='';
        }
        $this->Cell($w,$h,$txt,$border,$ln,$align,$fill,$link);
        if ($fit)
            $this->_out('BT '.($scale ? '100 Tz' : '0 Tc').' ET');
    }

    function CellFitScale($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,true,false);
    }

    function CellFitScaleForce($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,true,true);
    }

    function CellFitSpace($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,false,false);
    }

    function CellFitSpaceForce($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,false,true);
    }
}

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
$GLOBALS['mobile'] = $row['mobile'] ?? '';

$address = [];
if ($row_temp) {
	$address = json_decode($row_temp['address'] ?? '', true);
}
if (!is_array($address)) { $address = []; }

$GLOBALS["gross_total"] = '0';
$GLOBALS["si_no"] = $si_no;
$GLOBALS["dt"] = !empty($row['si_date']) ? date('d-m-Y', strtotime($row['si_date'])) : '';
$GLOBALS['add1'] = $address["address_1"] ?? '';
$GLOBALS['add2'] = $address["address_2"] ?? '';
$GLOBALS['city'] = $address["city"] ?? '';
$GLOBALS['pincode'] = $address["pincode"] ?? '';
$GLOBALS['state'] = $row_temp["state"] ?? '';
$GLOBALS['client'] = $row_temp['print_name'] ?? '';

$flag = 1;

if(($row_temp["state"] ?? '') == 'WEST BENGAL'){
    $flag = 0;
}

$pdf = new PDF_AutoPrint('P','mm','A5');
$pdf->SetMargins(10, 10, 10);
$pdf->AddPage();
$pdf->SetFont('Arial','',10);

$y = $pdf->getY();
$count = 1;
$total = 0;

$header = array('SN', 'Item', 'Unit Price', 'Quantity', 'Total');
$data = array();

$l = is_array($items['product'] ?? null) ? sizeof($items['product']) : 0;

for($i=0;$i<$l;$i++){
    $line_item = array();
    $line_item[] = $count++;
    $line_item[] = $items['product'][$i]."\n".$items['long_desc'][$i];
    $line_item[] = $formatter->formatCurrency($items['price'][$i], 'INR');
    $line_item[] = $items['quantity'][$i];
    $line_item[] = $formatter->formatCurrency($items['price'][$i] * $items['quantity'][$i], 'INR');
    $total += $items['price'][$i] * $items['quantity'][$i];
    $data[] = $line_item;
}
$pdf->InvoiceTable($header, $data);

$w = array(10, 55, 18, 15, 30);

$pdf->setX(10);
$pdf->SetFont('Arial','B',10);
$pdf->CellFitScale($w[0],6,'','1',0);
$pdf->CellFitScale($w[1],6,'TOTAL','1',0,'R');
$pdf->CellFitScale($w[2],6,'','1',0,'R');
$pdf->CellFitScale($w[3],6,'','1',0,'R');
$pdf->CellFitScale($w[4],6,$formatter->formatCurrency($total, 'INR'),'1',1,'R');

$pdf->SetFont('Arial','I',10);
$amntinwrds=numberToWords($total);
$temp = "Amount In Words : ".preg_replace('/\s+/', ' ', trim($amntinwrds));
$pdf->CellFitScale(128,6,$temp,0,1,'L');
$pdf->Ln(3);



$name = "S-".substr($GLOBALS["si_no"],7,4)."_".str_replace('-','',$GLOBALS["dt"]).".pdf";

if($pdf_type == 'print'){
    $pdf->AutoPrint();
    $pdf->output('I',$name);
}else{
    $pdf->output('D',$name);
}

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
