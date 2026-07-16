<?php

require_once "../connect.php";

$memberId = $_REQUEST['member_id'] ?? [];
if (!is_array($memberId)) {
    $memberId = $memberId !== '' ? [$memberId] : [];
}
$len = count($memberId);
$final_items = array('product' => array(), 'quantity' => array(), 'unit' => array(), 'price' => array(), 'discount' => array(), 'hsn' => array(), 'desc' => array(), 'tax' => array(), 'long_desc' => array());
$final_addons = array('freight' => 0, 'pf' => 0, 'discount' => 0);
$q_no = '';
$mobile = '';

for ($i = 0; $i < $len; $i++) {
    $member = $memberId[$i];
    $sql = "SELECT * FROM quotation WHERE quotation_no = '$member'";
    $query = $db->query($sql);
    $result = ($query) ? $query->fetch_assoc() : null;
    if (!$result) {
        continue;
    }
    $q_no = $result['quotation_no'] ?? '';
    $mobile = $result['mobile'] ?? '';
    $items = json_decode($result['items'] ?? '', true);
    if (!is_array($items) || !isset($items['product']) || !is_array($items['product'])) {
        continue;
    }
    $item_len = count($items['product']);

    for ($j = 0; $j < $item_len; $j++) {
        $final_items['product'][] = $items['product'][$j] ?? '';
        $final_items['group'][] = $items['group'][$j] ?? '';
        $final_items['quantity'][] = $items['quantity'][$j] ?? '';
        $final_items['unit'][] = $items['unit'][$j] ?? '';
        $final_items['price'][] = $items['price'][$j] ?? '';
        $final_items['discount'][] = $items['discount'][$j] ?? '';
        $final_items['hsn'][] = $items['hsn'][$j] ?? '';
        $final_items['desc'][] = $items['desc'][$j] ?? '';
        $final_items['tax'][] = $items['tax'][$j] ?? '';
        $final_items['long_desc'][] = $items['long_desc'][$j] ?? '';
    }

    $addons = json_decode($result['addons'] ?? '', true);
    if (is_array($addons)) {
        $final_addons['freight'] += (float)($addons['freight']['value'] ?? 0);
        $final_addons['pf'] += (float)($addons['pf']['value'] ?? 0);
    }
}

$data = array('items' => json_encode($final_items), 'addons' => json_encode($final_addons), 'q_no' => $q_no, 'mobile' => $mobile);

$db->close();

echo json_encode($data);

?>
