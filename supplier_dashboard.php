<?php
include('assets/custom/connect.php');

$token = isset($_GET['token']) ? trim($_GET['token']) : '';
$selected_product = isset($_GET['product']) ? trim($_GET['product']) : '';
$supplier = null;

if($token !== ''){
    $safe_token = $db->real_escape_string($token);
    $sql_supplier = "SELECT id, name, print_name, portal_token FROM suppliers WHERE portal_token = '$safe_token' LIMIT 1";
    $query_supplier = $db->query($sql_supplier);
    if($query_supplier && $query_supplier->num_rows > 0){
        $supplier = $query_supplier->fetch_assoc();
        $now = date('Y-m-d H:i:s');
        $db->query("UPDATE suppliers SET token_last_used = '$now' WHERE id = '".$supplier['id']."'");
    }
}

function addLedgerRow(&$ledger, $product, $date, $reference, $type, $in, $out, $rate = ''){
    if(!isset($ledger[$product])){
        $ledger[$product] = array();
    }
    $ledger[$product][] = array(
        "date" => $date,
        "reference" => $reference,
        "type" => $type,
        "in" => (float)$in,
        "out" => (float)$out,
        "rate" => $rate
    );
}

$ledger = array();
$summary = array();

if($supplier){
    $supplier_name = $db->real_escape_string($supplier['name']);
    $sql_material = "SELECT id, voucher_no, voucher_type, date, items FROM materials_received WHERE supplier_name = '$supplier_name' ORDER BY date ASC, id ASC";
    $query_material = $db->query($sql_material);
    if($query_material){
        while($row = $query_material->fetch_assoc()){
            $items = json_decode($row['items'], true);
            if(!isset($items['product']) || !is_array($items['product'])){
                continue;
            }
            $len = sizeof($items['product']);
            for($i=0;$i<$len;$i++){
                $product = isset($items['product'][$i]) ? $items['product'][$i] : '';
                $qty = isset($items['quantity'][$i]) ? (float)$items['quantity'][$i] : 0.0;
                $rate = isset($items['rate'][$i]) ? $items['rate'][$i] : '';
                if($product === '' || $qty <= 0){
                    continue;
                }
                if(!isset($summary[$product])){
                    $summary[$product] = array("received"=>0.0, "returned"=>0.0, "settled"=>0.0);
                }
                if($row['voucher_type'] === 'MRTN'){
                    $summary[$product]["returned"] += $qty;
                    addLedgerRow($ledger, $product, $row['date'], $row['voucher_no'], 'MRTN', 0, $qty, $rate);
                } else {
                    $summary[$product]["received"] += $qty;
                    addLedgerRow($ledger, $product, $row['date'], $row['voucher_no'], 'MRN', $qty, 0, $rate);
                }
            }
        }
    }

    $sql_settlement = "SELECT product_name, quantity, purchase_invoice_no, settled_on FROM consignment_settlements WHERE supplier_name = '$supplier_name' ORDER BY settled_on ASC, id ASC";
    $query_settlement = $db->query($sql_settlement);
    if($query_settlement){
        while($row = $query_settlement->fetch_assoc()){
            $product = $row['product_name'];
            $qty = (float)$row['quantity'];
            if(!isset($summary[$product])){
                $summary[$product] = array("received"=>0.0, "returned"=>0.0, "settled"=>0.0);
            }
            $summary[$product]["settled"] += $qty;
            addLedgerRow($ledger, $product, $row['settled_on'], $row['purchase_invoice_no'], 'SETTLEMENT', 0, $qty, '');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Consignment Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f6fa; margin: 0; }
        .wrap { max-width: 1100px; margin: 20px auto; background: #fff; padding: 20px; border-radius: 8px; }
        h1 { margin: 0 0 10px 0; font-size: 24px; }
        table { width: 100%; border-collapse: collapse; margin-top: 14px; }
        th, td { border: 1px solid #ddd; padding: 8px; font-size: 14px; }
        th { background: #f0f3ff; text-align: left; }
        .muted { color: #666; font-size: 13px; }
        .right { text-align: right; }
        .link { text-decoration: none; color: #1b5bd6; }
    </style>
</head>
<body>
<div class="wrap">
<?php if(!$supplier): ?>
    <h1>Seller dashboard unavailable</h1>
    <p class="muted">Invalid or expired link token.</p>
<?php else: ?>
    <h1>Consignment Dashboard - <?php echo htmlspecialchars($supplier['print_name'] !== '' ? $supplier['print_name'] : $supplier['name']); ?></h1>
    <p class="muted">Material Receipt Note (MRN) inward, Material Return Note (MRTN) outward, and Purchase Invoice settlements.</p>

    <?php if($selected_product === ''): ?>
        <h3>Item Summary</h3>
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="right">Received</th>
                    <th class="right">Returned</th>
                    <th class="right">Settled</th>
                    <th class="right">Outstanding</th>
                </tr>
            </thead>
            <tbody>
            <?php
            ksort($summary);
            foreach($summary as $product => $totals):
                $outstanding = $totals["received"] - $totals["returned"] - $totals["settled"];
                $product_url = 'supplier_dashboard.php?token=' . urlencode($token) . '&product=' . urlencode($product);
            ?>
                <tr>
                    <td><a class="link" href="<?php echo $product_url; ?>"><?php echo htmlspecialchars($product); ?></a></td>
                    <td class="right"><?php echo number_format($totals["received"], 2); ?></td>
                    <td class="right"><?php echo number_format($totals["returned"], 2); ?></td>
                    <td class="right"><?php echo number_format($totals["settled"], 2); ?></td>
                    <td class="right"><?php echo number_format($outstanding, 2); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <?php $product_key = $selected_product; ?>
        <p><a class="link" href="<?php echo 'supplier_dashboard.php?token=' . urlencode($token); ?>">&larr; Back to item list</a></p>
        <h3>Stock Flow Statement - <?php echo htmlspecialchars($product_key); ?></h3>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Reference</th>
                    <th>Type</th>
                    <th class="right">Rate</th>
                    <th class="right">In</th>
                    <th class="right">Out</th>
                    <th class="right">Balance</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $rows = isset($ledger[$product_key]) ? $ledger[$product_key] : array();
            usort($rows, function($a, $b){
                return strcmp($a['date'], $b['date']);
            });
            $balance = 0.0;
            foreach($rows as $entry):
                $balance += $entry["in"];
                $balance -= $entry["out"];
            ?>
                <tr>
                    <td><?php echo htmlspecialchars(date('d-m-Y', strtotime($entry["date"]))); ?></td>
                    <td><?php echo htmlspecialchars($entry["reference"]); ?></td>
                    <td><?php echo htmlspecialchars($entry["type"]); ?></td>
                    <td class="right"><?php echo ($entry["rate"] !== '' ? htmlspecialchars($entry["rate"]) : '-'); ?></td>
                    <td class="right"><?php echo number_format($entry["in"], 2); ?></td>
                    <td class="right"><?php echo number_format($entry["out"], 2); ?></td>
                    <td class="right"><?php echo number_format($balance, 2); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
<?php endif; ?>
</div>
</body>
</html>
