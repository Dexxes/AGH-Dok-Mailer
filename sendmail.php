<?php

// AGH-Dok-Mailer
// Entwickler: Julian von Bülow
// Lizenz: CC BY-SA 4.0 | https://creativecommons.org/licenses/by-sa/4.0/deed.de

require_once dirname(__FILE__) . '/PHPMailer/src/Exception.php';
require_once dirname(__FILE__) . '/PHPMailer/src/PHPMailer.php';
require_once dirname(__FILE__) . '/PHPMailer/src/SMTP.php';
require_once dirname(__FILE__) . '/config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function SendMail($message)
{
        $mail = new PHPMailer(true);

        //Server settings
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = SMTP_AUTH;
        $mail->Username = SMTP_USER;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port = SMTP_PORT;
        $mail->Encoding = "8bit";
        $mail->CharSet = "UTF-8";

        //Recipients
        $mail->setFrom(SMTP_SENDER_ADDRESS, SMTP_SENDER_NAME);
        $mail->addAddress(SMTP_RECIPIENT);

        //Content
        $mail->isHTML(true);
        $mail->Subject = MAIL_PREFIX . ' ' . MAIL_SUBJECT;
        $mail->Body    = $message;

        $mail->send();        
}

?>