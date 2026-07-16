<?php

session_start(); // Start session if needed
require_once '../assets/custom/connect.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the current maintenance mode status from the database
    $query = "SELECT is_maintenance_mode FROM setting WHERE id = 1";
    $result = $db->query($query);

    if ($result) {
        $row = $result->fetch_assoc(); // Fetch the result
        $current_status = $row['is_maintenance_mode'];

        // Toggle the maintenance mode status
        $new_status = $current_status ? 0 : 1;

        // Update the new status in the database
        $update_query = "UPDATE setting SET is_maintenance_mode = '$new_status' WHERE id = 1";

        if ($db->query($update_query) === TRUE) {
            // Destroy the session (log the user out)
            session_destroy();

            // Redirect to the login page or another page
            header('Location: index.php');
            exit();
        } else {
            echo "Error updating maintenance mode: " . $db->error;
        }
    } else {
        echo "Error fetching maintenance mode status: " . $db->error;
    }

    // Close the database connection
    $db->close();
}

?>
