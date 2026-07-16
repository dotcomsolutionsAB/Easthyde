<?php
date_default_timezone_set('Asia/Kolkata');
ini_set("display_errors", 1);

// Database Connection
function getDatabaseConnection() {
    $db = new mysqli('localhost', 'easthyde', 'r^8g24R1d', 'easthyde');
    if ($db->connect_errno) {
        die('Sorry, We are having some errors');
    }
    return $db;
}

// Set precision for PHP version 7.1+
if (version_compare(phpversion(), '7.1', '>=')) {
    ini_set('serialize_precision', -1);
}

// Main method to call the cron
function main() {
    $db = getDatabaseConnection();

    // Call the specific cron job function
    processSalesQueue($db);

    // Close database connection
    $db->close();
}

// Cron Job to Process Sales Queue
function processSalesQueue($db) {
    $apiUrl = "https://graph.facebook.com/v19.0/357370407455461/messages";
    $accessToken = "EAAEqC1znq1MBOwsToIozZCB2QslimXlqLJO6xdRZC2x5PMTqKfPdZA7TtjBH6YTTh6jRS5mRV5JKoEkiQccjdGAx8kItaxeiJVzUe8fckCRZBZANu2sjzFiiKFvUAYZAwwGQza3ploD5heDHm3IduT9ZAFioRsUUaQsu8m8Ah2XimStQRMqBwCusecFJqUbesufjZBZAyZBlE6oZCfKKVZCSaiqs";

    // Fetch pending messages from queue
    $sql = "SELECT * FROM tblwa_sales_queue WHERE status = 0 LIMIT 300";
    $result = $db->query($sql);
    if (!$result) {
        die("Error fetching data: " . $db->error);
    }
    $rows = $result->fetch_all(MYSQLI_ASSOC);

    foreach ($rows as $row) {
        $to = $row['to'];
        
        // Decode the content field to retrieve template data
        $content = json_decode($row['content'], true);
        
        if (!isset($content['name']) || !isset($content['language']) || !isset($content['components'])) {
            // Skip processing if required template fields are missing
            echo "Invalid template structure for row ID {$row['id']}\n";
            continue;
        }

        // Extract template details
        $template_name = $content['name'];
        $language_code = $content['language']['code'];
        $components = $content['components'];

        // Prepare message data for the WhatsApp API
        $data = [
            "messaging_product" => "whatsapp",
            "to" => $to,
            "type" => "template",
            "template" => [
                "name" => $template_name,
                "language" => ["code" => $language_code],
                "components" => $components
            ]
        ];

        // Send message and get response
        $response = sendCurlRequest($data, $apiUrl, $accessToken);

        // Update queue status and store response
        $update_sql = "UPDATE tblwa_sales_queue SET status = 1, response = '" . $db->real_escape_string(json_encode($response)) . "' WHERE id = " . $row['id'];
        $db->query($update_sql);
    }
}

// Send CURL request to WhatsApp API
function sendCurlRequest($data, $apiUrl, $accessToken) {
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer " . $accessToken,
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        echo 'CURL Error: ' . curl_error($ch);
    }
    curl_close($ch);

    return json_decode($response, true);
}

// Run the main function
main();
