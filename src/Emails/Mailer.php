<?php

namespace Udacity\Emails;

use Monolog\Logger;

/**
 * class responsible for sending emails
 * 
 * the environment must be set so that emails can be actually sent with the `mailutils msmtp msmtp-mta` packages
 * 
 */
final class Mailer
{

    /**
     * checks is msmtp config is set
     * 
     * @param string $msmtprcPath
     * 
     * @throws MsmtprcNotSetException
     *
     * @return void
     */
    public static function checkMsmtprc(string $msmtprcPath = '/etc/msmtprc'): void {
        if (!file_exists($msmtprcPath)) {
            throw new MsmtprcNotSetException();
        }
    }

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
     * @throws Exception if `msmtprc` is not set
     * 
     * @return void actually sends the email
     */
    public static function sendEmail(
        string $recipientEmail, 
        string $subject, 
        string $htmlEmail,
        string $msmtprcPath = '/etc/msmtprc',
        ?Logger $logger = null
    ): void {
        self::checkMsmtprc($msmtprcPath);
        $delivered = mail(
            $recipientEmail,
            $subject,
            $htmlEmail,
            "MIME-Version: 1.0" . "\r\n". "Content-type: text/html; charset=utf8" . "\r\n"
        );
        if (!$delivered) {
            throw new EmailNotDeliveredException();
            if (!is_null($logger)) {
                $logger->alert("email not sent to $recipientEmail");
            }
        }
    }
}