<?php declare(strict_types=1);

namespace Tests\Integration\Apps;

require_once '/var/www/tests/fixtures/classes/DummyApp.php';

use DummyApp;
use Exception;
use PHPUnit\Framework\TestCase;
use Tests\Integration\EnvLoaderTrait;

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

    public function testConstructWithIncompleteEnvThrowsRuntimeException() {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The app environment is not properly set');
        unset($_ENV['DB_HOST']);
        $actual = new DummyApp('/var/www/tests/fixtures/envs/incomplete');
    }

}