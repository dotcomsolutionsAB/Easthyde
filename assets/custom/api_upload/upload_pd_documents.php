<?php
session_start();

$file = $_FILES['file'];

$validator = array('success' => false, 'messages' => '');

$fileName = $_FILES["file"]["name"]; // The file name
$fileTmpLoc = $_FILES["file"]["tmp_name"]; // File in the PHP tmp folder
$fileType = $_FILES["file"]["type"]; // The type of file it is
$fileSize = $_FILES["file"]["size"]; // File size in bytes
$fileErrorMsg = $_FILES["file"]["error"]; // 0 for false... and 1 for true
$fileName = preg_replace('#[^a-z.0-9]#i', '', $fileName); // filter the $filename
$kaboom = explode(".", $fileName); // Split file name into an array using the dot
$fileExt = strtolower(end($kaboom)); // Now target the last array element to get the file extension

// START PHP Image Upload Error Handling --------------------------------
if (!$fileTmpLoc) { // if file not chosen
    $validator['messages'] = "ERROR: Please browse for a file before clicking the upload button.";
    echo json_encode($validator);
    exit();
} 
else if ($fileErrorMsg == 1) { // if file upload error key is equal to 1
    $validator['messages'] = "ERROR: An error occured while processing the file. Try again.";
    echo json_encode($validator);
    exit();
}
// END PHP Image Upload Error Handling ----------------------------------

$_SESSION['pd_whatsapp'] .= $fileName.',';

// Place it into your "uploads" folder mow using the move_uploaded_file() function
$moveResult = move_uploaded_file($fileTmpLoc, "../../uploads/pd_whatsapp/$fileName");

$validator['success'] = "True";
$validator['messages'] = "Successfully uploaded";
$validator['pd_whatsapp'] = $fileName;

// Check to make sure the move result is true before continuing
if ($moveResult != true) {
    $validator['messages'] = "ERROR: File not uploaded. Try again.";
    echo json_encode($validator);
    exit();
}

echo json_encode($validator);

?>