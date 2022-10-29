<?php declare(strict_types=1);

namespace Tests\Unit\Emails;

use Udacity\Emails\Mailer;
use PHPUnit\Framework\TestCase;
use Udacity\Exceptions\MsmtprcNotSetException;

final class MailerTest extends TestCase
{

    public function testEmailWithNonExistingMsmtprcThrows() {
        $this->expectException(MsmtprcNotSetException::class);
        $this->expectExceptionMessage("`msmptrc` file not configured");
        $this->expectExceptionCode(1);
        Mailer::sendEmail(
            "some_recipient",
            "some_subjet",
            "some_email",
            "/etc/nonexistingrc"
        );
    }

}