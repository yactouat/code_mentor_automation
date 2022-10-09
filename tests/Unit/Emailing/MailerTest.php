<?php declare(strict_types=1);

namespace Tests\Unit\Emailing;

use App\Emailing\Mailer;
use PHPUnit\Framework\TestCase;

final class MailerTest extends TestCase
{

    public function testEmailWithNonExistingMsmtprcThrows() {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("You have not set `msmtp` correctly");
        Mailer::sendEmail(
            "some_recipient",
            "some_subjet",
            "some_email",
            "/etc/nonexistingrc"
        );
    }

}