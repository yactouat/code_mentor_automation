<?php

namespace App\Emailing;

/**
 * class responsible for sending emails
 * 
 * the environment must be set so that emails can be actually sent with the `mailutils msmtp msmtp-mta` packages
 * 
 */
final class Mailer
{

    /**
     * sends an email
     * 
     * sent emails are HTML emails
     *
     * @param string $recipientEmail
     * @param string $subject
     * @param string $htmlEmail better rendering if you pass HTML formatted text in there
     * @param string $msmtprcPath the path to your `msmtp`config
     * 
     * @return void actually sends the email
     */
    public static function sendEmail(
        string $recipientEmail, 
        string $subject, 
        string $htmlEmail,
        string $msmtprcPath = '/etc/msmtprc'
    ): void {
        if (!file_exists($msmtprcPath)) {
            throw new \Exception("You have not set `msmtp` correctly", 1);
        }
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