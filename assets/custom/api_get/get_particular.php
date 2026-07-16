<?php

session_start();
require_once "../connect.php";

// $term = '%';
$contents = json_encode($_REQUEST['q']);
$jsonObj = json_decode($contents);
$key = 'term';
$term = $jsonObj->$key;
$master = $_REQUEST['master'];
$type = $_REQUEST['type'];

// $supplier = '%';

$json = array("results"=>array());

if($type == 0)
{
     $sql = "SELECT * FROM purchase_invoice WHERE `pi_no` LIKE '%$term%' AND `supplier_name` LIKE '%$master%'";
     $query = $db->query($sql);
     while($row = $query->fetch_assoc()){

          $json["results"][] = ['id'=>$row['pi_no'], 'text'=>$row['pi_no']];
     }

     $sql = "SELECT * FROM payments WHERE `py_no` LIKE '%$term%' AND `supplier` LIKE '%$master%'";
     $query = $db->query($sql);
     while($row = $query->fetch_assoc()){

          $json["results"][] = ['id'=>$row['py_no'], 'text'=>$row['py_no']];
     }
}
else{
     $sql = "SELECT * FROM sales_invoice WHERE `si_no` LIKE '%$term%' AND `client_name` LIKE '%$master%'";
     $query = $db->query($sql);
     while($row = $query->fetch_assoc()){

          $json["results"][] = ['id'=>$row['si_no'], 'text'=>$row['si_no']];
     }

     $sql = "SELECT * FROM receipts WHERE `r_no` LIKE '%$term%' AND `client` LIKE '%$master%'";
     $query = $db->query($sql);
     while($row = $query->fetch_assoc()){

          $json["results"][] = ['id'=>$row['r_no'], 'text'=>$row['r_no']];
     }
}



echo json_encode($json);

?>