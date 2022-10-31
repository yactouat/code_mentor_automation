<?php declare(strict_types=1);

namespace Tests\Integration\Apps\Web\Controllers\Resource;

use PHPUnit\Framework\TestCase;
use Tests\Integration\TestsLoaderTrait;
use Udacity\Apps\Web\Controllers\Resource\EmailsController;

final class EmailsControllerTest extends TestCase {

    use TestsLoaderTrait;

    protected function setUp(): void
    {
        $this->loadEnv();
    }

    public function testPersistUnauthedGetsLoginPage() {
        $expected = str_replace(
            [' ', "\n"], ['', ''],
            file_get_contents('/var/www/tests/fixtures/views/session-leads.login.html')
        );
        $ctlr = new EmailsController();
        $actual = $ctlr->persist();
        $this->assertEquals($expected, str_replace([' ', "\n"], ['', ''], $actual));
    }

    public function testPersistUnauthedGets401Code() {
        $expected = 401;
        $ctlr = new EmailsController();
        $ctlr->persist();
        $actual = $ctlr->getStatusCode();
        $this->assertEquals($expected, $actual);
    }

    public function testGetEmailsPayloadToValidateReturnsExpectedPayload() {
        $fileKey = 'test';
        $_FILES[$fileKey] = [
            'name' => 'some_file_name'
        ];
        $_POST['some_key'] = 'some value';
        $expected = [
            'some_key' => 'some value',
            $fileKey => [
                'name' => 'some_file_name'
            ]
        ];
        $ctlr = new EmailsController();
        $actual = $ctlr->getEmailsPayloadToValidate($fileKey);
        $this->assertEquals($expected, $actual);
    }

    public function testGetEmailsPayloadToValidateWithUnsetFileKeyReturnsExpectedPayload() {
        $fileKey = 'test';
        $_POST['some_key'] = 'some value';
        $expected = [
            'some_key' => 'some value',
            $fileKey => []
        ];
        $ctlr = new EmailsController();
        $actual = $ctlr->getEmailsPayloadToValidate($fileKey);
        $this->assertEquals($expected, $actual);
    }

}