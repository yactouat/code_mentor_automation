<?php declare(strict_types=1);

namespace Tests\Integration\Apps;

require_once '/var/www/tests/fixtures/classes/DummyApp.php';

use DummyApp;
use PHPUnit\Framework\TestCase;
use Tests\Integration\EnvLoaderTrait;
use Udacity\Exceptions\BadEnvException;

final class AppTest extends TestCase {

    use EnvLoaderTrait;

    protected function setUp(): void
    {
        $this->loadEnv();
    }

    protected function tearDown(): void
    {
        $_ENV['DB_HOST'] = 'mariadbtest';
    }

    public function testConstructWithIncompleteEnvThrowsBadEnvException() {
        $this->expectException(BadEnvException::class);
        $this->expectExceptionMessage('the app environment is not properly set');
        unset($_ENV['DB_HOST']);
        $actual = new DummyApp('/var/www/tests/fixtures/envs/incomplete');
    }

}