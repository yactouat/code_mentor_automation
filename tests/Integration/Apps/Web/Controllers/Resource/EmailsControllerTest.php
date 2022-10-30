<?php declare(strict_types=1);

namespace Tests\Integration\Apps\Web\Controllers\Resource;

use PHPUnit\Framework\TestCase;
use Tests\Integration\Apps\Web\AuthenticateTrait;
use Tests\Integration\EnvLoaderTrait;
use Udacity\Apps\Web\Controllers\Resource\EmailsController;

final class EmailsControllerTest extends TestCase {

    use AuthenticateTrait;
    use EnvLoaderTrait;

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

    // TODO
    // public function testPersistAuthedWithInalidInputGetsRelevantErrorFeedback() {
    // }
    // public function testPersistAuthedWithValidInputGetsSuccessFeedback() {
    // }

}