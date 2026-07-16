<?php 

session_start();
require_once "../connect.php";

$pi = $_REQUEST['pi'];

$sql = "SELECT * FROM purchase_invoice WHERE pi_no = '$pi'";
$query = $db->query($sql);
$row = $query->fetch_assoc();

$po_numbers = json_decode($row['po_no'], true);
$k_len = sizeof($po_numbers);

for($k=0;$k<$k_len;$k++){
    $po = $po_numbers[$k];
    // echo $po.'<br/>';
    $sql_temp = "SELECT * FROM purchase_order WHERE po_no LIKE '%$po%'";
    $query_temp = $db->query($sql_temp);

    while($row_temp = $query_temp->fetch_assoc()){
        $items = json_decode($row_temp['items'], true);
        $l = sizeof($items['product']);

        for($i=0;$i<$l;$i++){
            $items['received'][$i] = 0;
        }

        $flag = 0;
        $sql1 = "SELECT * FROM purchase_invoice WHERE po_no LIKE '%$po%'";
        $query1 = $db->query($sql1);
        // $phase = 1;
        while($row1 = $query1->fetch_assoc())
        {
            $flag = 1;
            $items1 = json_decode($row1['items'], true);
            $len = sizeof($items1['product']);
            for($j=0;$j<$len;$j++)
            {
                $pr = $items1['product'][$j];
                $qty = $items1['quantity'][$j];
                // echo "Phase ".$phase++."<br/>";
                for($i=0;$i<$l;$i++)
                {
                    $pr_name = $items['product'][$i];

                    if($pr_name == $pr)
                    {
                        // echo "Loop Variable i ".$i."<br/>";
                        // echo $pr_name." --- </br>";
                        $temp = $items['received'][$i] + $qty;
                        // echo "Temp --- ".$temp."</br>";
                        if($temp > $items['quantity'][$i])
                        {
                            $balance = $items['quantity'][$i] - $items['received'][$i];
                            // echo "Balance --- ".$balance."</br>";
                            $items['received'][$i] += $balance;
                            $qty = $qty - $balance;
                            // echo $i." - Received : ".$items['received'][$i]."<br/>";
                        }
                        else
                        {
                            $items['received'][$i] += $qty;
                            // echo "Break --- ".$items['received'][$i]."</br>";
                            break;
                        }
                        
                    }
                }
            }

        }
        for($i=0;$i<$l;$i++)
        {
            $quantity = $items['quantity'][$i];
            $received = $items['received'][$i];
            // echo $quantity."<br/>";
            // echo $received."<br/>";
            if($quantity > $received)
            {
                $flag = $flag*0;
            }
            else
            {
                $flag = $flag*1;
            }
        }

        $items_arr = json_encode($items);

        if($flag == 1)
        {
            $sql3 = "UPDATE purchase_order SET items = '$items_arr', status='1' WHERE po_no = '$po'";
            $query3 = $db->query($sql3);
        }
        else
        {
            $sql3 = "UPDATE purchase_order SET items = '$items_arr', status='0' WHERE po_no = '$po'";
            $query3 = $db->query($sql3);
        }
    }
}



?>