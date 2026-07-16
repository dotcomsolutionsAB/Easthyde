<?php
require_once "../connect.php";

// Get the ID of the record to be deleted
$id = $_POST['id'] ?? '';

// Check if the ID is valid
if ($id) {
    // Delete the record from the database
    $sql = "DELETE FROM expense WHERE id = '$id'";
    if ($db->query($sql) === TRUE) {
        echo json_encode(["success" => true, "messages" => "Expense deleted successfully!"]);
    } else {
        echo json_encode(["success" => false, "messages" => "Error deleting expense!"]);
    }
} else {
    echo json_encode(["success" => false, "messages" => "Invalid ID!"]);
}
?>
