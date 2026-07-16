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
        $this->SetDrawColor(0,0,0);
        $this->Rect(8, 8, 132, 194, 'D');

        $this->SetFont('Arial','B',12);
        $this->Cell(128,3,'',0,2,C);
        $this->Cell(128,5,'',0,2,C);
        $this->SetFont('Arial','B',10);
        $this->Cell(49,5,"",'',0,C);
        $this->Cell(30,5,"Assembly Details",'B',0,C);
        $this->Cell(49,5,"",'',1,C);
        $this->Cell(128,3,"",0,2,C);

        $this->SetFont('Arial','',9);
        $this->Cell(5,5,"",0,0,C);
        $this->Cell(59,5,'',0,0,L);
        $this->Cell(59,5,'',0,1,R);
        $this->Cell(128,3,"",0,2,C);

	}

	// Page footer
	function Footer()
	{
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

$id = $_REQUEST['id'];
$pdf_type = $_REQUEST['type'];

$pdf = new PDF_AutoPrint('P','mm',array(210,148));
$pdf->SetAutoPageBreak(true, 10);
$pdf->setMargins(10, 10);
$title = "Assembly Details";
$pdf->SetTitle($title);

$pdf->AliasNbPages();
$pdf->AddPage();

$sql = "SELECT * FROM assembly_operation WHERE `id` = '$id'";
$query = $db->query($sql);
$row = $query->fetch_assoc();

$composite = $row['composite'];
$operation = $row['operation'];
$quantity = $row['quantity'];
$invoice = $row['invoice'];
$date = date('d-m-Y', strtotime($row['log_date']));


$items = json_decode($row['items'],true);
$len = sizeof($items['product']);

$pdf->setXY('10','26');

$pdf->SetFont('Arial','B',9);
$pdf->Cell(128,6,'Date : '.$date,'',2,L);
$pdf->Cell(128,6,'Composite : '.$composite,'',2,L);
$pdf->Cell(128,6,'Quantity : '.$quantity,'',2,L);
$pdf->Cell(128,6,'Operation : '.$operation,'',2,L);
$pdf->Cell(128,6,'Invoice : '.$invoice,'',2,L);

$pdf->setXY('10','58');

$pdf->Cell(10,6,'S.No',1,0,C);
$pdf->Cell(80,6,'Spare Product(s)',1,0,C);
$pdf->Cell(38,6,'Quantity',1,1,C);

$pdf->SetFont('Arial','',9);

for($i=0;$i<$len;$i++)
{
    $pdf->Cell(10,6,$i+1,1,0,C);
    $pdf->Cell(80,6,$items['product'][$i],1,0,C);
    $pdf->Cell(38,6,$items['quantity'][$i],1,1,C);
}


$name = "Assembly.pdf";

if($pdf_type == 'print'){
	// $pdf->AutoPrint();
	$pdf->output('I',$name);
}else{
	$pdf->output('D',$name);
}

?>
