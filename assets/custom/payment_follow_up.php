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
		
	}

	// Page footer
	function Footer()
	{

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

    function SetDash($black=null, $white=null)
    {
        if($black!==null)
            $s=sprintf('[%.3F %.3F] 0 d',$black*$this->k,$white*$this->k);
        else
            $s='[] 0 d';
        $this->_out($s);
    }

}

//--------------------------------------------- Define Variables & Fetch Data from Database --------------------------------------

$ids = $_REQUEST['ids'];
$id_array = explode(',',$ids);

$len = sizeof($id_array);
$date = date('d-m-Y', strtotime('today'));
$pdf_type = 'print';

$pdf = new PDF_AutoPrint('P','mm',array(297,210));
// $pdf->SetAutoPageBreak(true, 35);
$pdf->setMargins(0, 0);
$title = "Payment Follow up";
$pdf->SetTitle($title);

$pdf->AddPage();
$pdf->setY(10);
$pdf->SetDash(2,2); //5mm on, 5mm off
$pdf->Line(10,75,200,75);
$pdf->Line(10,145,200,145);
$pdf->Line(10,215,200,215);
$pdf->Line(105,10,105,287);

$y_index = 0;

for($loop = 0;$loop < $len; $loop++){

	if($y_index >= 8 && $y_index % 8 == 0)
	{
		$pdf->AddPage();
		$pdf->setY(10);
        $pdf->SetDash(2,2); //5mm on, 5mm off
        $pdf->Line(10,75,200,75);
        $pdf->Line(10,145,200,145);
        $pdf->Line(10,215,200,215);
        $pdf->Line(105,10,105,287);

        $y_index = 0;
	}

    $pdf->SetDash(2,0);
	$client_id = $id_array[$loop];

	$sql = "SELECT * FROM clients WHERE id = '$client_id'";
	$query = $db->query($sql);
	$row = $query->fetch_assoc();

	$client = $row['name'];

	$sql_2 = "SELECT * FROM sales_invoice WHERE client_name = '$client' AND (status = '0' OR status = '2')  AND `series` = 'PRIMARY' ORDER BY si_date";
	$query_2 = $db->query($sql_2);
	$sn = 1;

	if($y_index % 2 == 0){
		$y = $y_index * 35 + 10;
		$pdf->setY($y);
		$pdf->SetFont('Arial','B',9);
		$pdf->Cell(105,5,'Ammar Industrial Corporation',0,2,C);
		$pdf->SetFont('Arial','I',8);
		$pdf->Cell(10,5,'',0,0,C);
		$pdf->CellFitScale(60,5,$row['print_name'],'B',0,L);
		$pdf->Cell(25,5,$date,'B',1,R);
		while($row_2 = $query_2->fetch_assoc()){

            $am_paid = 0;
            $si_no = $row_2['si_no'];
            $sql_check = "SELECT * FROM receipts WHERE `sales_invoice` LIKE '%$si_no%'";
            $query_check = $db->query($sql_check);
            while($row_check = $query_check->fetch_assoc()){
            
                
                $inv_array = json_decode($row_check['sales_invoice'], true);
                $lenn = sizeof($inv_array['si_no']);
                for($i=0;$i<$lenn;$i++){
                    if($inv_array['si_no'][$i] == $si_no){
                        $am_paid += $inv_array['amount'][$i];
                    }
                }
            }

            $due = $row_2['total'] - $am_paid;

            if($am_paid > 0)
            {
    			$pdf->Cell(10,6,'',0,0,C);
    			$pdf->Cell(5,6,$sn++,0,0,C);
    			$pdf->CellFitScale(15,6,date('d-m-Y', strtotime($row_2['si_date'])),0,0,L);
    			$pdf->CellFitScale(45,6,$row_2['si_no'],0,0,L);
                $temp = '( '.money_format('%!i', $row_2['total']).' ) '.money_format('%!i', $due);
    			$pdf->CellFitScale(20,6,$temp,0,1,R);
            }else{
                $pdf->Cell(10,6,'',0,0,C);
                $pdf->Cell(5,6,$sn++,0,0,C);
                $pdf->CellFitScale(15,6,date('d-m-Y', strtotime($row_2['si_date'])),0,0,L);
                $pdf->CellFitScale(45,6,$row_2['si_no'],0,0,L);
                $pdf->CellFitScale(20,6,money_format('%!i', $row_2['total']),0,1,R);
            }
		}

	}
	else{
		$y = ($y_index-1) * 35 + 10;
		$pdf->setY($y);
		$pdf->setX(105);
		$pdf->SetFont('Arial','B',9);
		$pdf->Cell(105,5,'Ammar Industrial Corporation',0,2,C);
		$pdf->SetFont('Arial','I',8);
		$pdf->Cell(10,5,'',0,0,C);
		$pdf->CellFitScale(60,5,$row['print_name'],'B',0,L);
		$pdf->Cell(25,5,$date,'B',1,R);
		while($row_2 = $query_2->fetch_assoc()){

            $am_paid = 0;
            $si_no = $row_2['si_no'];
            $sql_check = "SELECT * FROM receipts WHERE `sales_invoice` LIKE '%$si_no%'";
            $query_check = $db->query($sql_check);
            while($row_check = $query_check->fetch_assoc()){
                
                $inv_array = json_decode($row_check['sales_invoice'], true);
                $lenn = sizeof($inv_array['si_no']);
                for($i=0;$i<$lenn;$i++){
                    if($inv_array['si_no'][$i] == $si_no){
                        $am_paid += $inv_array['si_no'][$i];
                    }
                }
            }

            $due = $row_2['total'] - $am_paid;

            if($am_paid > 0)
            {
    			$pdf->setX(105);
    			$pdf->Cell(10,6,'',0,0,C);
    			$pdf->Cell(5,6,$sn++,0,0,C);
    			$pdf->CellFitScale(15,6,date('d-m-Y', strtotime($row_2['si_date'])),0,0,L);
    			$pdf->CellFitScale(45,6,$row_2['si_no'],0,0,L);
                $temp = '( '.money_format('%!i', $row_2['total']).' ) '.money_format('%!i', $due);
    			$pdf->CellFitScale(20,6,money_format('%!i', $temp),0,2,R);
            }else{
                $pdf->setX(105);
                $pdf->Cell(10,6,'',0,0,C);
                $pdf->Cell(5,6,$sn++,0,0,C);
                $pdf->CellFitScale(15,6,date('d-m-Y', strtotime($row_2['si_date'])),0,0,L);
                $pdf->CellFitScale(45,6,$row_2['si_no'],0,0,L);
                $pdf->CellFitScale(20,6,money_format('%!i', $row_2['total']),0,2,R);
            }
		}
	}
    $y_index++;
}




$name = "Payment_Followup.pdf";

if($pdf_type == 'print'){
	// $pdf->AutoPrint();
	$pdf->output('I',$name);
}else{
	$pdf->output('D',$name);
}

?>
