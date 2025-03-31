<?php
require $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

$mail = new PHPMailer(true);

// Configuration SMTP
$mail->isSMTP();
$mail->SMTPAuth = true;
$mail->Host = 'smtp.gmail.com';

$mail->Username = 'zenpoxa@gmail.com';
$mail->Password = 'gnis gjqr wmqh fpar';

$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;

return $mail;
