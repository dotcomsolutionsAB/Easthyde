<?php
include ("connect.php");

try {
    // Step 1: Fetch receipts with ADVANCE in sales_invoice JSON
    $query = "SELECT * FROM receipts WHERE sales_invoice LIKE '%\"ADVANCE\"%'";

    $result = $db->query($query);

    if ($result->num_rows > 0) {
        while ($receipt = $result->fetch_assoc()) {
            $receiptId = $receipt['id'];
            $clientName = $receipt['client']; // Client name from receipts table
            $salesInvoice = json_decode($receipt['sales_invoice'], true);

            // Step 2: Extract advance amount
            $advanceAmount = 0;
            foreach ($salesInvoice['si_no'] as $key => $value) {
                if ($value === 'ADVANCE') {
                    $advanceAmount = (float)$salesInvoice['amount'][$key];
                    break;
                }
            }

            if ($advanceAmount > 0) {
                // Step 3: Find the first unpaid invoice for this client
                $invoiceQuery = "SELECT id, si_no, total FROM sales_invoice 
                                 WHERE client_name = '$clientName' AND status = '0' LIMIT 1";
                $invoiceResult = $db->query($invoiceQuery);

                if ($invoiceResult->num_rows > 0) {
                    $invoice = $invoiceResult->fetch_assoc();
                    $invoiceNo = $invoice['si_no'];    // Invoice number
                    $invoiceTotal = (float)$invoice['total']; // Invoice total amount

                    // Step 4: Calculate due amount
                    $dueAmount = $invoiceTotal - $advanceAmount;

                    // Step 5: Update sales_invoice JSON in receipts table
                    $updatedSalesInvoice = [
                        'si_no' => [$invoiceNo],
                        'amount' => [$advanceAmount],
                        'due' => [$dueAmount]
                    ];
                    $updatedSalesInvoiceJson = json_encode($updatedSalesInvoice);

                    $updateReceiptQuery = "UPDATE receipts 
                                           SET sales_invoice = '$updatedSalesInvoiceJson' 
                                           WHERE id = '$receiptId'";
                    $db->query($updateReceiptQuery);

                    // Step 6: Mark the invoice as partially paid (status = 2)
                    $updateInvoiceQuery = "UPDATE sales_invoice 
                                           SET status = 2 
                                           WHERE id = {$invoice['id']}";
                    $db->query($updateInvoiceQuery);

                    // Log success
                    echo "Updated Receipt ID: $receiptId with Invoice No: $invoiceNo, Advance Amount: $advanceAmount, Due: $dueAmount.\n";
                } else {
                    echo "No unpaid invoices found for client: $clientName.\n";
                }
            }
        }
    } else {
        echo "No receipts found with ADVANCE entries.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
