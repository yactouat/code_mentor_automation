<?php declare(strict_types=1);

namespace Tests\Integration\Apps;

use PHPUnit\Framework\TestCase;
use Tests\TestsHelperTrait;
use Udacity\Apps\CLI\CLIApp;
use Udacity\Apps\Web\WebApp;
use Udacity\Exceptions\BadEnvException;

final class AppTest extends TestCase {

    use TestsHelperTrait;

    protected function setUp(): void
    {
        $this->loadEnv();
    }

    public function testWebAppConstructWithIncompleteEnvThrowsBadEnvException() {
        $this->expectException(BadEnvException::class);
        $this->expectExceptionMessage('the app environment is not properly set');
        unset($_ENV['DB_HOST']);
        $actual = new WebApp('/var/www');
    }

    public function testCliAppConstructWithIncompleteEnvThrowsBadEnvException() {
        $this->expectException(BadEnvException::class);
        $this->expectExceptionMessage('the app environment is not properly set');
        unset($_ENV['DB_HOST']);
        $actual = new CLIApp('/var/www');
    }

}