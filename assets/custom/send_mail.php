<?php

use PHPMailer\PHPMailer\PHPMailer;
require '../plugins/custom/PHPMailer/src/PHPMailer.php';
require '../plugins/custom/PHPMailer/src/SMTP.php';

$subject = 'Demo Email';
$message = 'Dear Sir/Madam,<br/> Please find the quotation attached to this email.<br/><br/><i>Thanking You,</i><br/><strong>M.M. Lucky Enterprise</strong><br/>26, Strand Road, Ground Floor,<br/>Kolkata - 700 001, West Bengal, India<br/>Ph No. +91 6289778473<br/>Website : www.easthyde.com';

//PHPMailer Object
$mail = new PHPMailer(true); //Argument true in constructor enables exceptions

//From email address and name
$mail->From = "no-reply@ammarindustrial.in";
$mail->FromName = "M.M. Lucky Enterprise";

//To address and name
$mail->addAddress("kburhanuddin12@gmail.com", "Burhanuddin");
$mail->addAddress("dotcomsolutiononline@gmail.com", "Burhanuddin");

//Address to which recipient will reply
$mail->addReplyTo("kburhanuddin12@gmail.com", "Reply");

//Send HTML or Plain Text email
$mail->isHTML(true);

$mail->Subject = "Subject Text";
$mail->Body = $message;
// $mail->AltBody = "This is the plain text version of the email content";

try {
    $mail->send();
    $validator['success'] = true;
    $validator['message'] = 'Successfully Sent';
} catch (Exception $e) {
    $validator['success'] = false;
    $validator['message'] = "Mailer Error: " . $mail->ErrorInfo;
}

echo json_encode($validator);
?>