<?php

namespace App;

final class Mailer
{
    public static function sendEmail(string $recipientEmail, string $subject, string $htmlEmail): void {
        $delivered = mail(
            $recipientEmail,
            $subject,
            $htmlEmail,
            "MIME-Version: 1.0" . "\r\n". "Content-type: text/html; charset=utf8" . "\r\n"
        );
        if (!$delivered) {
            echo PHP_EOL."email not sent to $recipientEmail".PHP_EOL;
        }
    }
}