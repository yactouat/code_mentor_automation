<?php declare(strict_types=1);

namespace Tests\Integration\Services;

use PHPUnit\Framework\TestCase;
use Udacity\Services\LoggerService;

final class LoggerServiceTest extends TestCase {

    protected function setUp(): void
    {
        $_ENV = [];
    }

    public function testgetLogsDirWithoutTestingEnvReturnsDefaultLogsDir() {
        $expected = '/var/www/data/logs/php/';
        $actual = LoggerService::getService('test_logger')->{'getLogsDir'}();
        $this->assertEquals($expected, $actual);
    }

    public function testgetLogsDirWithTestingEnvReturnsCorrectLogsDir() {
        $_ENV['IS_TESTING'] = true;
        $expected = '/var/www/tests/fixtures/logs/php/';
        $actual = LoggerService::getService('test_logger')->{'getLogsDir'}();
        $this->assertEquals($expected, $actual);
    }


}