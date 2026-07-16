<?php
include ("../connect.php");
include ("../php_replace_improper.php");

session_start();

$validator = array("success" => false, "messages" => "There was some error saving the records");

// Getting form data
$edit_pi_id = $_REQUEST['edit_pi_id'] ?? '';
$purchase_quotation_no = $_REQUEST['purchase_quotation_no'] ?? '';
$pi_supplier = replace_improper($_REQUEST['pi_supplier'] ?? '');
$mobile = replace_improper($_REQUEST['mobile'] ?? '');
$pq_date_raw = $_REQUEST['purchase_invoice_date'] ?? '';
$purchase_invoice_date = ($pq_date_raw !== '') ? date('Y-m-d', strtotime($pq_date_raw)) : '';

$quotation_file = $_FILES['quotation_file'] ?? null;
$file_path = '';
$public_url = '';

if (is_array($quotation_file) && $quotation_file['error'] === UPLOAD_ERR_OK) {
   // Assuming 'assets/uploads/p_quotations' is within the public directory of your website
$upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/assets/uploads/p_quotations/';
// Ensure this directory exists and is writable
    $file_name = time() . '_' . basename($quotation_file['name']);
    $target_file = $upload_dir . $file_name;


    // Ensure the directory exists
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true); // Create the directory if it doesn't exist
    }

    // Move the uploaded file to the target directory
    if (move_uploaded_file($quotation_file['tmp_name'], $target_file)) {
        $file_path = $target_file;  // Set the file path for further processing
        $public_url = 'https://easthyde.com/assets/uploads/p_quotations/' . urlencode($file_name);

    } 
}



$log_user = $_SESSION['username'] ?? '';
$log_date = date('Y-m-d', strtotime("today"));

// Preparing item data
$purchase_invoice = $_REQUEST['purchase_invoice'] ?? [];
if (!is_array($purchase_invoice)) { $purchase_invoice = []; }
$items = array('product'=>[], 'desc'=>[], 'long_desc'=>[], 'quantity'=>[], 'unit'=>[], 'price'=>[], 'discount'=>[], 'hsn'=>[], 'tax'=>[]);

foreach ($purchase_invoice as $invoice) {
    $items['product'][] = replace_improper($invoice['pi_product_name']);
    $items['desc'][] = replace_improper($invoice['pi_product_description']);
    $items['long_desc'][] = replace_improper($invoice['pi_product_add_description']);
    $items['quantity'][] = replace_improper($invoice['pi_qty']);
    $items['unit'][] = replace_improper($invoice['pi_unit']);
    $items['price'][] = replace_improper($invoice['pi_rate']);
    $items['discount'][] = replace_improper($invoice['pi_dsc']);
    $items['hsn'][] = replace_improper($invoice['pi_hsn']);
    $items['tax'][] = replace_improper($invoice['pi_tax']);
}
$items_json = json_encode($items);

// Addons data
$addons = array(
    'pf' => array('value' => replace_improper_amount($_REQUEST['pi_pf'] ?? ''), 'cgst' => '', 'sgst' => '', 'igst' => ''),
    'freight' => array('value' => replace_improper_amount($_REQUEST['pi_freight'] ?? ''), 'cgst' => '', 'sgst' => '', 'igst' => ''),
    'roundoff' => replace_improper_amount($_REQUEST['pi_round'] ?? '')
);
$addons_json = json_encode($addons);

// Total and tax data
$pi_total_final = replace_improper_amount($_REQUEST['pi_total_final'] ?? '');
$pi_tax_final = replace_improper_amount($_REQUEST['pi_tax_final'] ?? '');
$tax = array('cgst' => '', 'sgst' => '', 'igst' => '');
$tax_json = json_encode($tax);

$status = 0;

if ($edit_pi_id == '') {
    // Fetch the counter for purchase quotations
    $sql_counter = "SELECT * FROM counter WHERE `key` = 'purchase_quotation'";
    $query_counter = $db->query($sql_counter);
    if ($query_counter && $query_counter->num_rows > 0) {
        $row_counter = $query_counter->fetch_assoc();
        $row_counter_arr = json_decode($row_counter['value'] ?? '', true);
        if (is_array($row_counter_arr) && isset($row_counter_arr['prefix'][0], $row_counter_arr['number'][0], $row_counter_arr['postfix'][0])) {
            // Increment the counter
            $row_counter_arr['number'][0] = $row_counter_arr['number'][0] + 1;

            // Update the counter in the database
            $counter_array = json_encode($row_counter_arr);
            $sql_update_counter = "UPDATE counter SET `value` = '$counter_array' WHERE `key` = 'purchase_quotation'";
            $db->query($sql_update_counter);

            // Insert new record
            $sql = "INSERT INTO purchase_quotation 
                    (`supplier_name`, `mobile`, `pq_no`, `pi_date`, `items`, `addons`, `total`, `tax`, `status`, `log_user`, `log_date`, `file_path`) 
                    VALUES ('$pi_supplier', '$mobile', '$purchase_quotation_no', '$purchase_invoice_date', '$items_json', '$addons_json', '$pi_total_final', '$tax_json', '$status', '$log_user', '$log_date', '$public_url')";

            if ($db->query($sql) === true) {
                $validator['success'] = true;
                $validator['messages'] = "Successfully Added";
            }
        }
    }
} else {
    // Update existing record
    $sql = "UPDATE purchase_quotation 
            SET `supplier_name` = '$pi_supplier', `mobile` = '$mobile',`pq_no`='$purchase_quotation_no', `pi_date` = '$purchase_invoice_date', `items` = '$items_json', `addons` = '$addons_json', `total` = '$pi_total_final', `tax` = '$tax_json', `log_user` = '$log_user', `log_date` = '$log_date', `file_path` = '$public_url' 
            WHERE `id` = '$edit_pi_id'";
           // echo $sql;

    if ($db->query($sql) === true) {
        $validator['success'] = true;
        $validator['messages'] = "Successfully Updated";
      
    }
}

echo json_encode($validator);
?>
