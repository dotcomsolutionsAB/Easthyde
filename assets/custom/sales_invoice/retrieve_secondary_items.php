<?php
session_start();
require_once "../connect.php";
setlocale(LC_MONETARY, 'en_IN');

$pagination = $_REQUEST['pagination'] ?? [];
$query_array = $_REQUEST['query'] ?? [];
$sort_array = $_REQUEST['sort'] ?? [];

$searchquery = $query_array['generalSearch'] ?? '';

$array = [];
$count=1;
$sql = "SELECT * FROM sales_invoice WHERE `series` = 'SECONDARY' ORDER BY `si_date` DESC";
$query = $db->query($sql);
if ($query) {
while($row = $query->fetch_assoc()){

    $items  = json_decode($row['items'] ?? '', true);
    $len = (is_array($items) && isset($items['product']) && is_array($items['product'])) ? sizeof($items['product']) : 0;

    for($i=0;$i<$len;$i++)
    {
        $flag = 0;
        $arr_len = sizeof($array);
        for($j=0;$j<$arr_len;$j++){
            $pr = $items['product'][$i];
            if($pr == $array[$j]['Product']){
                if($items['effective_quantity'][$i] > 0)
                {
                    $qty = $array[$j]['Quantity'] + $items['effective_quantity'][$i];
                    $si = $array[$j]['SI'].', '.'<a href="../assets/custom/sales_secondary_print.php?id='.$row['si_no'].'&type=print" target="_blank">'.$row['si_no']."</a>";
                    $array[$j]['Quantity'] = $qty;
                    $array[$j]['SI'] = $si;
                    $flag = 1;
                }
            }
        }

        if($flag == 0)
        {
            $pr = $items['product'][$i];
            $hsn = $items['hsn'][$i];
            if(strpos((string)$pr, (string)$searchquery) !== false || strpos((string)$hsn, (string)$searchquery) !== false || $searchquery == '')
            {
                $array[] = array(      
                    'RecordID' => $count,
                    'SN' => $count++,
                    'Product' => $items['product'][$i],
                    'HSN' => $items['hsn'][$i],
                    'Quantity' => $items['effective_quantity'][$i],
                    'SI' => '<a href="../assets/custom/sales_secondary_print.php?id='.$row['si_no'].'&type=print" target="_blank">'.$row['si_no']."</a>"
                );
            }
        }
    }
}
}

$total = sizeof($array);
$perpage = (int)($pagination['perpage'] ?? 10);
$page = (int)($pagination['page'] ?? 1);
if ($perpage < 1) { $perpage = 10; }
if ($page < 1) { $page = 1; }

if($total < $perpage){
    $perpage = $total;
}
$start = ($page - 1) * $perpage;
$pages = $total / $perpage;

$output = array('meta'=> array("page"=> $page, "pages"=> $pages, "perpage"=> $perpage,"total"=> $total,"sort"=> 'asc', "field"=> 'SN'), 'data' => array());

for($j=$start,$count=0;$count<$perpage && isset($array[$j]);$j++){

    // $product = $array[$j]['Product'];

    // if(strpos($product, $query) !== false){

        $output['data'][] = $array[$j];
        $count++;
    // }
}

echo json_encode($output);
?>
