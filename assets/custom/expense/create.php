<?php
// Include your database connection and helper functions
include ("../connect.php");
include ("../php_replace_improper.php");

session_start();

// Assume the username comes from a session
$log_user = $_SESSION['username'];

// Retrieve form data
$id = isset($_POST['id']) ? replace_improper($_POST['id']) : ''; // Optional ID for edit functionality
$description = replace_improper($_POST['description']);
$amount = replace_improper($_POST['amount']);
$bank = replace_improper($_POST['bank']);
$category = replace_improper($_POST['category']);
$date = replace_improper($_POST['date']);
$date = date('Y-m-d', strtotime($date)); // Convert to database date format

// Initialize the response array
$validator = array("success" => false, "messages" => "There was some error saving the record");

// Check if ID is provided (for update functionality)
if(!empty($id)) {
    // If an ID is provided, update the existing record
    $sql = "UPDATE expense SET 
            `description` = '$description', 
            `amount` = '$amount', 
            `account` = '$bank', 
            `category` = '$category', 
            `date` = '$date', 
            `created_by` = '$log_user' 
            WHERE `id` = '$id'";
    
    $query = $db->query($sql);

    // Check if the update query was successful
    if($query === true) {
        $validator['success'] = true;
        $validator['messages'] = "Expense successfully updated!";
    } else {
        $validator['messages'] = "Error: " . $db->error;
    }
} else {
    // If no ID is provided, perform an insert
    $sql = "INSERT INTO expense (`description`, `amount`, `account`, `category`, `date`, `created_by`) 
            VALUES ('$description', '$amount', '$bank', '$category', '$date', '$log_user')";
    
    $query = $db->query($sql);

    // Check if the insert query was successful
    if($query === true) {
        $validator['success'] = true;
        $validator['messages'] = "Expense successfully added!";
    } else {
        $validator['messages'] = "Error: " . $db->error;
    }
}

// Return the response as JSON
echo json_encode($validator);
?>
