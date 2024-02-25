<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/..');
$dotenv->load();

var_dump($_ENV['MAIL_HOST']);


$mail = new PHPMailer(true);


$data = json_decode(file_get_contents("php://input"), true);
$phone = $data['phone'] ?? 'Ismeretlen'; // Alapértelmezett érték, ha nem érkezik telefon

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->isSMTP();
    $mail->Host = $_ENV['MAIL_HOST'];
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV['MAIL_USER'];
    $mail->Password = $_ENV['MAIL_PASSWORD'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = $_ENV['MAIL_PORT'];
    $mail->CharSet = 'UTF-8';

    //Recipients
    $mail->setFrom($_ENV['MAIL_USER'], 'Szabó Iroda');
    $mail->addAddress($_ENV['MAIL_ADDRESS'], 'Szabó Iroda');

    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = $_ENV['MAIL_SUBJECT'];
    $mail->Body    = "Az alábbí számró visszahívást kértek:<br>Telefonszám: {$phone}";
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>