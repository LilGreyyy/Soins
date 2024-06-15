<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . "/mail/vendor/autoload.php"; // Путь к автозагрузчику PHPMailer

function getMailer($email) {
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->SMTPAuth = true;

    $mail->Host = "smtp.gmail.com"; // Адрес SMTP-сервера Gmail
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->Username = "your-gmail@example.com"; // Ваша почта Gmail
    $mail->Password = "your-gmail-password"; // Пароль от вашей почты Gmail

    $mail->isHtml(true);
    $mail->setFrom('your-gmail@example.com', 'Your Name');

    $mail->addAddress($email);

    return $mail;
}