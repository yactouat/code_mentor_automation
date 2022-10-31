<?php

namespace Udacity\Emails;

use Monolog\Logger;
use Udacity\Exceptions\EmailNotDeliveredException;
use Udacity\Exceptions\FileWritePermissionException;
use Udacity\Exceptions\MsmtprcNotSetException;

/**
 * class responsible for sending emails
 * 
 * the environment must be set so that emails can be actually sent with the `mailutils msmtp msmtp-mta` packages
 * 
 */
final class Mailer
{

    /**
     * constructs a msmtprc file and puts in the right place
     *
     * @param string $email - the session lead email
     * @param string $gAppPass - the input Google App Password
     * @return void
     */
    public static function buildMsmtprc(string $email, string $gAppPass): void {
        $destPath = !empty($_ENV['isTesting']) ? '/etc/msmtprc.test' : '/etc/msmtprc';
        \file_put_contents($destPath, sprintf(
            \file_get_contents('/var/www/scripts/msmtp/msmtprc.template'),
            $email,
            $email,
            $gAppPass
        ));
    }

    /**
     * checks is msmtp config is set
     * 
     * @param string $msmtprcPath
     * 
     * @throws MsmtprcNotSetException
     * @throws FileWritePermissionException
     *
     * @return void
     */
    public static function checkMsmtprc(string $msmtprcPath = '/etc/msmtprc'): void {
        if (!file_exists($msmtprcPath)) {
            throw new MsmtprcNotSetException();
        }
        if(!is_writable($msmtprcPath)) {
            throw new FileWritePermissionException();
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
     * @param ?Logger $logger optional logger
     * 
     * @throws EmailNotDeliveredException if email is not delivered
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