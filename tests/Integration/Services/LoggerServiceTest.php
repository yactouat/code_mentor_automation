<?php declare(strict_types=1);

namespace Tests\Integration\Services;

use PHPUnit\Framework\TestCase;
use Tests\TestsHelperTrait;
use Udacity\Apps\CLI\CLIApp;
use Udacity\Apps\Web\WebApp;
use Udacity\Services\DatabaseService;
use Udacity\Services\LoggerService;
use Udacity\Services\ServicesContainer;

final class LoggerServiceTest extends TestCase {

    use TestsHelperTrait;

    protected function setUp(): void
    {
        $this->resetLogsFiles();
    }

    public function testgetLogsDirWithoutTestingEnvReturnsDefaultLogsDir() {
        unset($_ENV['IS_TESTING']);
        $expected = '/var/www/data/logs/php/';
        $actual = LoggerService::getAppInstanceLogger()->{'getLogsDir'}();
        $this->assertEquals($expected, $actual);
    }

    public function testgetLogsDirWithTestingEnvReturnsCorrectLogsDir() {
        $this->loadEnv();
        $expected = '/var/www/tests/fixtures/logs/php/';
        $actual = LoggerService::getAppInstanceLogger()->{'getLogsDir'}();
        $this->assertEquals($expected, $actual);
    }

    public function testLoggerServiceWithWebAppWritesLogsAtTheRightPlace() {
        $this->loadEnv();
        $expected = "testLoggerServiceWithWebAppWritesLogsAtTheRightPlace";
        $app = new WebApp('/var/www/tests/fixtures');
        LoggerService::getAppInstanceLogger()->debug('testLoggerServiceWithWebAppWritesLogsAtTheRightPlace');
        $actual = file_get_contents('/var/www/tests/fixtures/logs/php/test_web.log');
        $this->assertTrue($this->stringIsContainedInAnother($expected, $actual));
    }

    public function testLoggerServiceWithCliAppWritesLogsAtTheRightPlace() {
        $this->loadEnv('cli');
        $expected = "testLoggerServiceWithCliAppWritesLogsAtTheRightPlace";
        $app = new CLIApp('/var/www/tests/fixtures');
        LoggerService::getAppInstanceLogger()->debug('testLoggerServiceWithCliAppWritesLogsAtTheRightPlace');
        var_dump(LoggerService::getAppInstanceLogger());
        $actual = file_get_contents('/var/www/tests/fixtures/logs/php/test_cli.log');
        $this->assertTrue($this->stringIsContainedInAnother($expected, $actual));
    }

    public function testLoggerServiceWithDbWritesLogsAtTheRightPlace() {
        $expected = "testLoggerServiceWithDbWritesLogsAtTheRightPlace";
        $this->loadEnv();
        ServicesContainer::resetServices();
        $db = DatabaseService::getService('test_read_db');
        LoggerService::getService('test_db_logger')->debug('testLoggerServiceWithDbWritesLogsAtTheRightPlace');
        $actual = file_get_contents('/var/www/tests/fixtures/logs/php/test_db.log');
        $this->assertTrue($this->stringIsContainedInAnother($expected, $actual));
    }

}