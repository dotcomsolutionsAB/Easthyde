<?php

require_once "../connect.php";

$memberId = $_REQUEST['member_id'] ?? [];
if (!is_array($memberId)) {
    $memberId = $memberId !== '' ? [$memberId] : [];
}
$len = count($memberId);
$final_items = array('product' => array(), 'quantity' => array(), 'desc' => array(), 'long_desc' => array(), 'stock' => array());
$enq_date = array();
$cl_enquiry = array();

for ($i = 0; $i < $len; $i++) {
    $member = $memberId[$i];
    $sql = "SELECT * FROM enquiry WHERE enquiry_no = '$member'";
    $query = $db->query($sql);
    $result = ($query) ? $query->fetch_assoc() : null;
    if (!$result) {
        continue;
    }

    $enq_date[] = date('d-m-Y', strtotime($result['enquiry_date'] ?? ''));
    $cl_enquiry[] = $result['cl_enquiry_no'] ?? '';

    $items = json_decode($result['items'] ?? '', true);
    if (!is_array($items) || !isset($items['product']) || !is_array($items['product'])) {
        continue;
    }
    $item_len = count($items['product']);

    for ($j = 0; $j < $item_len; $j++) {
        $final_items['product'][] = $items['product'][$j] ?? '';
        $final_items['quantity'][] = $items['quantity'][$j] ?? '';
        $final_items['desc'][] = $items['desc'][$j] ?? '';
        $final_items['long_desc'][] = $items['long_desc'][$j] ?? '';
        $final_items['stock'][] = $items['stock'][$j] ?? '';
    }
}

$data = array('items' => json_encode($final_items), 'enquiry_date' => json_encode($enq_date), 'cl_enquiry' => json_encode($cl_enquiry));

$db->close();

echo json_encode($data);

?>
