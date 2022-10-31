<?php declare(strict_types=1);

namespace Tests\Integration\Apps;

require_once '/var/www/tests/fixtures/classes/DummyApp.php';

use DummyApp;
use PHPUnit\Framework\TestCase;
use Tests\Integration\TestsLoaderTrait;
use Udacity\Exceptions\BadEnvException;

final class AppTest extends TestCase {

    use TestsLoaderTrait;

    protected function setUp(): void
    {
        $this->loadEnv();
    }

    public function testConstructWithIncompleteEnvThrowsBadEnvException() {
        $this->expectException(BadEnvException::class);
        $this->expectExceptionMessage('the app environment is not properly set');
        unset($_ENV['DB_HOST']);
        $actual = new DummyApp();
    }

}