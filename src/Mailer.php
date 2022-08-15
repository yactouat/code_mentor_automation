<?php

namespace App;

/**
 * class responsible for sending emails
 * 
 * the environment must be set so that emails can be actually sent (for instance with the `mailutils msmtp msmtp-mta` packages)
 * 
 */
final class Mailer
{

    /**
     * sends an email
     * 
     * sent emails are HTML emails
     *
     * @param  mixed $recipientEmail
     * @param  mixed $subject
     * @param  mixed $htmlEmail better rendering if you pass HTML formatted text in there
     * 
     * @return void actually sends the email
     */
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