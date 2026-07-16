<?php
session_start();
require_once "../connect.php";
setlocale(LC_MONETARY, 'en_IN');

$start = $_SESSION['start'];
$end = $_SESSION['end'];
$date = date('Y-m-d',strtotime('today'));
$today = date('d-m-Y',strtotime('today'));

if(strtotime($end) > strtotime($date)){
	$end = $date;
}
$id = $_REQUEST['member_id'];

$output = array("message"=>"", "status"=>"400");

$sql_temp = "SELECT * FROM clients WHERE id = '$id'";
$query_temp = $db->query($sql_temp);
$row_temp = $query_temp->fetch_assoc();

$client = $row_temp['name'];

//Message Creation
$output['message'] = '*Kind Attention M/s : '.$row_temp['print_name'].'*

Sir / Madam,                                         Dt: _'.$today.'_

You requested to clear the due payments, as detailed below

Due Payment details :
';

$count = 1;

$opening_due = $row_temp['opening_balance'] - $row_temp['paid'];

if($opening_due > 0){
	$output['message'] .= $count++.'.   *Opening Balance*,   *Rs. '.money_format('%!i',$opening_due).'*
';
}

$sql_2 = "SELECT * FROM sales_invoice WHERE client_name = '$client' AND (status = '0' OR status = '2') AND `series` = 'PRIMARY' ORDER BY si_date";
$query_2 = $db->query($sql_2);
while($row_2 = $query_2->fetch_assoc()){
    $due            = $row_2['total'];
    $p_date         = date('d-m-Y',strtotime($row_2['si_date']));
    $particulars    = $row_2['si_no'];

    $total = money_format('%!i',$row_2['total']);

    $due = money_format('%!i',$due);

    if($row_2['status'] == '2')
    {
        $sql_3 = "SELECT * FROM receipts WHERE sales_invoice LIKE '%$particulars%' ORDER BY `id` DESC LIMIT 1";
        $query_3 = $db->query($sql_3);
        $row_3 = $query_3->fetch_assoc();
            
        $sales_invoice = json_decode($row_3['sales_invoice'],true); 
        $len = sizeof($sales_invoice['si_no']);

        for($i = 0;$i<$len;$i++)
        {
            if($sales_invoice['si_no'][$i] == $particulars)
            {
                $amount         = $sales_invoice['amount'][$i];
                $prev_due       = $sales_invoice['due'][$i];

                $due = $prev_due - $amount;
                $due = money_format('%!i',$due).' out of Rs. ~'.$total.'~';

                break;
            }
        }
    }

    $output['message'] .= $count++.'.   *'.$particulars.'*,   Dt: '.$p_date.',   *Rs. '.$due.'*
';
}


$output['message'] .= '
For any query / clarification / discrepancies, please feel free to contact

*Thanks*,
*Easthyde*
_Ph_ : *6289778473*
_Email_ : mmleind@gmail.com
_Website_ : www.easthyde.com
$output['status'] = "200";

$db->close();
 
echo json_encode($output);

?>