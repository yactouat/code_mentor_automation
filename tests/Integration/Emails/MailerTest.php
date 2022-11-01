<?php declare(strict_types=1);

namespace Tests\Integration\Emails;

use PHPUnit\Framework\TestCase;
use Tests\TestsHelperTrait;
use Udacity\Emails\Mailer;
use Udacity\Exceptions\EmailNotDeliveredException;
use Udacity\Exceptions\MsmtprcNotSetException;
use Udacity\Exceptions\WrongEmailFormatException;

final class MailerTest extends TestCase
{

    use TestsHelperTrait;

    public function testSendEmailWithoutMailingConfSetLogsErrorMessage() {
        $this->resetLogsFiles();
        $this->setLoggersWithMode();
        $expected = "email not sent to johndoe@gmail.com";
        try {
            Mailer::sendEmail('johndoe@gmail.com', 'test', '<p>my email</p>');
        } catch (EmailNotDeliveredException $ende) {
            // echo $ende->getMessage();
        }
        $actual = file_get_contents('/var/www/tests/fixtures/logs/php/web.log');
        $this->assertTrue($this->stringIsContainedInAnother($expected, $actual));
    }

    public function testEmailWithNonExistingMsmtprcThrows() {
        $this->setLoggersWithMode();
        $this->expectException(MsmtprcNotSetException::class);
        $this->expectExceptionMessage("`msmptrc` file not configured");
        $this->expectExceptionCode(1);
        Mailer::sendEmail(
            'johndoe@gmail.com',
            'some_subjet',
            'some_email',
            '/etc/nonexistingrc'
        );
    }

    public function testEmailWithInvalidEmailThrowsCorrectException() {
        $this->setLoggersWithMode();
        $this->expectException(WrongEmailFormatException::class);
        $this->expectExceptionMessage('wrong email format');
        $this->expectExceptionCode(1);
        Mailer::sendEmail(
            'johndoe@.com',
            'some_subjet',
            'some_email'
        );
    }
}