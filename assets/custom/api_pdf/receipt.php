<?php
session_start();
// Adjust if needed for FPDF path

require("../../plugins/global/fpdf/fpdf.php"); // Adjust the path if necessary
require_once "../connect.php"; // Database connection


class PDF extends FPDF
{
    // Header for PDF
    function Header()
    {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Receipts Report', 0, 1, 'C');
        $this->Ln(5);
        
        // Table header with MultiCell for wrapping headers
        $this->SetFont('Arial', 'B', 10);
        $this->SetWidths([20, 40, 30, 30, 30, 20, 40, 40, 30]);
        $this->Row(['Invoice No', 'Client', 'Amount', 'Date', 'Account', 'Mode', 'Instrument', 'Bank Name', 'Inst. Date']);
    }

    // Footer for PDF
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    // Function to set widths for MultiCell columns
    function SetWidths($w)
    {
        $this->widths = $w;
    }

    // Function to handle each row with MultiCell
    function Row($data)
    {
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }
        $h = 5 * $nb;
        $this->CheckPageBreak($h);
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $this->MultiCell($w, 5, $data[$i], 1, 'L');
            $this->SetXY($this->GetX() + $w, $this->GetY() - $h);
        }
        $this->Ln($h);
    }

    // Check if a page break is needed
    function CheckPageBreak($h)
    {
        if ($this->GetY() + $h > $this->PageBreakTrigger) {
            $this->AddPage($this->CurOrientation);
        }
    }

    // Calculate the number of lines needed for a cell
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
            if ($c == ' ' || $c == "\n") {
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
}

$ids = '(' . $_REQUEST['ids'] . ')';
$dt_start = $_SESSION['start'];
$dt_end = $_SESSION['end'];

// Initialize PDF
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);

// SQL Query based on IDs or Date Range
if ($_REQUEST['ids'] == 'all') {
    $sql = "SELECT * FROM receipts WHERE `date` BETWEEN '$dt_start' AND '$dt_end' ORDER BY `date`";
} else {
    $sql = "SELECT * FROM receipts WHERE id IN $ids";
}

$query = $db->query($sql);

// Populate PDF with data
while ($row = $query->fetch_assoc()) {
    $invoice = $row['si_no'];
    $client = htmlspecialchars($row['client'], ENT_QUOTES, 'UTF-8');
    $amount = $row['amount'];
    $date = date('Y-m-d', strtotime($row['date']));
    $account = $row['account'];
    $mode = $row['mode'];
    $instrument = $row['instrument'];
    $bank = htmlspecialchars($row['bank_name'], ENT_QUOTES, 'UTF-8');
    $insdate = date('Y-m-d', strtotime($row['ins_date']));

    // Write row data using MultiCell for text wrapping
    $pdf->Row([$invoice, $client, $amount, $date, $account, $mode, $instrument, $bank, $insdate]);
}

// Save or output the PDF
$pdf->Output('D', 'receipts.pdf'); // Forces download
// or
// $pdf->Output('F', 'path/to/receipts.pdf'); // Save on server
