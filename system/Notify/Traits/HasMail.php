<?php

namespace System\Notify\Traits;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use System\Config\Config;

trait HasMail
{
    public static function sendMail($to, $subject, $body)
    {
        $mail = new PHPMailer(true);
        $mail->Encoding = 'base64';
        $mail->CharSet = 'UTF-8';
        try {
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ];
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = Config::get('MAIL_HOST');
            $mail->SMTPAuth = Config::get('mail.SMTP.AUTH');
            $mail->Username = Config::get('MAIL_USERNAME');
            $mail->Password = Config::get('MAIL_PASSWORD');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = (int) Config::get('MAIL_PORT');
            $mail->setFrom(Config::get('mail.SMTP.FROM.MAIL'), Config::get('mail.SMTP.FROM.NAME'));
            if (is_array($to)) {
                foreach ($to as $toMail) {
                    $mail->addAddress($toMail);
                }
            } else {
                $mail->addAddress($to);
            }
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->AltBody = e($body);
            $res = $mail->send();

            return $res;
        } catch (Exception $e) {
            throw new \Exception("Message could not be sent. Mailer Error: {$mail->ErrorInfo}", 500);

            return false;
        }
    }
}
