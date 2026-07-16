<?php

// Enable error reporting for debugging
ini_set('display_errors', '1');
error_reporting(E_ALL);

// Start output buffering to prevent premature output
ob_start();

// Include FPDF library
require("../../plugins/global/fpdf/fpdf.php"); // Adjust the path if necessary
require_once "../connect.php"; // Database connection

// Extend the FPDF class to create custom headers and footers
class PDF extends FPDF
{
    var $widths;
    var $aligns;

    function SetWidths($w)
    {
        $this->widths = $w;
    }

    function SetAligns($a)
    {
        $this->aligns = $a;
    }

    function Row($data)
    {
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }
        $h = 7 * $nb;
        $this->CheckPageBreak($h);
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            $x = $this->GetX();
            $y = $this->GetY();
            $this->Rect($x, $y, $w, $h);
            $this->MultiCell($w, 7, $data[$i], 0, $a);
            $this->SetXY($x + $w, $y);
        }
        $this->Ln($h);
    }

    function NbLines($w, $txt)
    {
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

    function CheckPageBreak($h)
    {
        if ($this->GetY() + $h > $this->PageBreakTrigger) {
            $this->AddPage($this->CurOrientation);
        }
    }

    function Header()
    {
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(0, 10, 'Sales Invoice Report', 0, 1, 'C');
        $this->Ln(5);
        $w = array(10, 35, 25, 50, 20, 20, 40, 25, 20, 20, 20, 30);
        $this->SetWidths($w);
        $this->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'R', 'R', 'R', 'R', 'R'));
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);
        $headers = array('SN', 'Invoice No', 'Date', 'Client Name', 'State', 'HSN Code', 'GSTIN', 'Amount (Excl. GST)', 'CGST', 'SGST', 'IGST', 'Total (Incl. GST)');
        $this->Row($headers);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 'all';
$ids = $ids !== 'all' ? '(' . $ids . ')' : 'all';
$dt_start = isset($_SESSION['start']) ? $_SESSION['start'] : date('Y-m-d', strtotime('-7 days'));
$dt_end = isset($_SESSION['end']) ? $_SESSION['end'] : date('Y-m-d');

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 9);

$w = array(10, 35, 25, 50, 20, 20, 40, 25, 20, 20, 20, 30);
$pdf->SetWidths($w);
$pdf->SetAligns(array('C', 'C', 'C', 'L', 'C', 'C', 'C', 'R', 'R', 'R', 'R', 'R'));

$sn = 1;
$total_amount = $total_cgst = $total_sgst = $total_igst = $grand_total = 0;

if ($ids === 'all') {
    $sql = "SELECT * FROM sales_invoice WHERE si_date BETWEEN '$dt_start' AND '$dt_end' AND series != 'SECONDARY' AND cancelled = 0 ORDER BY si_no";
} else {
    $sql = "SELECT * FROM sales_invoice WHERE id IN $ids AND series != 'SECONDARY' AND cancelled = 0";
}

$query = $db->query($sql);

if ($query->num_rows > 0) {
    while ($row = $query->fetch_assoc()) {
        $invoice = $row['si_no'];
        $client = $row['client_name'];
        $invoice_date = date('Y-m-d', strtotime($row['si_date']));
        $total_invoice = $row['total'];
        $state = $row['state'];

        $sql_pull = "SELECT gstin FROM clients WHERE name = '$client'";
        $query_pull = $db->query($sql_pull);
        $row_pull = $query_pull->fetch_assoc();
        $gstin = $row_pull['gstin'];

        $item_details = json_decode($row['items'], true);
        $l = sizeof($item_details['product']);
        $hsn_data = [];

        for ($i = 0; $i < $l; $i++) {
            $hsn_code = isset($item_details['hsn'][$i]) ? $item_details['hsn'][$i] : '';
            $quantity = isset($item_details['quantity'][$i]) ? $item_details['quantity'][$i] : 0;
            $rate = isset($item_details['price'][$i]) ? $item_details['price'][$i] : 0;
            $amount_excl_gst = $quantity * $rate;
            $cgst = isset($item_details['cgst'][$i]) ? round($item_details['cgst'][$i], 2) : 0;
            $sgst = isset($item_details['sgst'][$i]) ? round($item_details['sgst'][$i], 2) : 0;
            $igst = isset($item_details['igst'][$i]) ? round($item_details['igst'][$i], 2) : 0;

            if (isset($hsn_data[$hsn_code])) {
                $hsn_data[$hsn_code]['amount'] += $amount_excl_gst;
                $hsn_data[$hsn_code]['cgst'] += $cgst;
                $hsn_data[$hsn_code]['sgst'] += $sgst;
                $hsn_data[$hsn_code]['igst'] += $igst;
            } else {
                $hsn_data[$hsn_code] = [
                    'amount' => $amount_excl_gst,
                    'cgst' => $cgst,
                    'sgst' => $sgst,
                    'igst' => $igst
                ];
            }
        }

        $first_row = true;
        foreach ($hsn_data as $hsn_code => $data) {
            $total_with_gst = $data['amount'] + $data['cgst'] + $data['sgst'] + $data['igst'];

            if ($first_row) {
                $row = array(
                    $sn, $invoice, $invoice_date, $client, $state, $hsn_code, $gstin,
                    number_format($data['amount'], 2), number_format($data['cgst'], 2), number_format($data['sgst'], 2),
                    number_format($data['igst'], 2), number_format($total_with_gst, 2)
                );
                $first_row = false;
            } else {
                $row = array('', '', '', '', '', $hsn_code, '',
                    number_format($data['amount'], 2), number_format($data['cgst'], 2), number_format($data['sgst'], 2),
                    number_format($data['igst'], 2), number_format($total_with_gst, 2)
                );
            }
            $pdf->Row($row);

            $total_amount += $data['amount'];
            $total_cgst += $data['cgst'];
            $total_sgst += $data['sgst'];
            $total_igst += $data['igst'];
            $grand_total += $total_with_gst;
        }
        $sn++;
    }

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Row(array(
        'TOTAL', '', '', '', '', '', '',
        number_format($total_amount, 2), number_format($total_cgst, 2), number_format($total_sgst, 2),
        number_format($total_igst, 2), number_format($grand_total, 2)
    ));
} else {
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'No sales invoice data found for the selected criteria.', 0, 1, 'C');
}

// Clear output buffer and send the PDF
ob_end_clean();
$filename = 'sales_report_' . date('Ymd') . '.pdf';
$pdf->Output('I', $filename);
?>
