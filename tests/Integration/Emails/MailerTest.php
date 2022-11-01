<?php declare(strict_types=1);

namespace Tests\Integration\Emails;

use PHPUnit\Framework\TestCase;
use Tests\TestsHelperTrait;
use Udacity\Emails\Mailer;
use Udacity\Exceptions\EmailNotDeliveredException;

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
}