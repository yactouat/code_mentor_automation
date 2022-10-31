<?php declare(strict_types=1);

namespace Tests\Unit\Apps\CLI\Commands;

use PHPUnit\Framework\TestCase;
use Udacity\Apps\CLI\Commands\SendEmailsToBehindStudentsCommand;

final class SendEmailsToBehindStudentsCommandTest extends TestCase {

    public function testCommandHelpIsAsExpected() {
        $expected = 'This command allows you to send emails in bulk to students that are behind in their Nanodegree program using a Udacity session report CSV file.';
        $this->assertEquals($expected, SendEmailsToBehindStudentsCommand::COMMAND_HELP);
    }

}

