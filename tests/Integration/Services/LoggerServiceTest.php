<?php declare(strict_types=1);

namespace Tests\Integration\Services;

use PHPUnit\Framework\TestCase;
use Tests\Traits\TestsLoaderTrait;
use Tests\Traits\TestsStringsTrait;
use Udacity\Apps\CLI\CLIApp;
use Udacity\Apps\Web\WebApp;
use Udacity\Services\DatabaseService;
use Udacity\Services\LoggerService;
use Udacity\Services\ServicesContainer;

final class LoggerServiceTest extends TestCase {

    use TestsLoaderTrait;
    use TestsStringsTrait;

    protected function setUp(): void
    {
        $this->loadEnv();
        foreach ([
            '/var/www/tests/fixtures/logs/php/web.log',
            '/var/www/tests/fixtures/logs/php/cli.log',
            '/var/www/tests/fixtures/logs/php/db.log'
        ] as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }

    }

    public function testgetLogsDirWithoutTestingEnvReturnsDefaultLogsDir() {
        unset($_ENV['IS_TESTING']);
        $expected = '/var/www/data/logs/php/';
        $actual = LoggerService::getAppInstanceLogger()->{'getLogsDir'}();
        $this->assertEquals($expected, $actual);
    }

    public function testgetLogsDirWithTestingEnvReturnsCorrectLogsDir() {
        $expected = '/var/www/tests/fixtures/logs/php/';
        $actual = LoggerService::getAppInstanceLogger()->{'getLogsDir'}();
        $this->assertEquals($expected, $actual);
    }

    public function testLoggerServiceWithWebAppWritesLogsAtTheRightPlace() {
        $expected = "testLoggerServiceWithWebAppWritesLogsAtTheRightPlace";
        $app = new WebApp('/var/www/tests/fixtures');
        LoggerService::getAppInstanceLogger()->debug('testLoggerServiceWithWebAppWritesLogsAtTheRightPlace');
        $actual = file_get_contents('/var/www/tests/fixtures/logs/php/web.log');
        $this->assertTrue($this->stringIsContainedInAnother($expected, $actual));
    }

    public function testLoggerServiceWithCliAppWritesLogsAtTheRightPlace() {
        $expected = "testLoggerServiceWithCliAppWritesLogsAtTheRightPlace";
        $_SERVER['PHP_SELF'] = '/var/www/bin/index.php';
        $app = new CLIApp('/var/www/tests/fixtures');
        LoggerService::getAppInstanceLogger()->debug('testLoggerServiceWithCliAppWritesLogsAtTheRightPlace');
        $actual = file_get_contents('/var/www/tests/fixtures/logs/php/cli.log');
        $this->assertTrue($this->stringIsContainedInAnother($expected, $actual));
    }

    public function testLoggerServiceWithDbWritesLogsAtTheRightPlace() {
        $expected = "testLoggerServiceWithDbWritesLogsAtTheRightPlace";
        $this->resetSuperGlobals();
        $this->setTestingEnv();
        ServicesContainer::resetServices();
        $db = DatabaseService::getService('test_read_db');
        LoggerService::getService('test_db_logger')->debug('testLoggerServiceWithDbWritesLogsAtTheRightPlace');
        $actual = file_get_contents('/var/www/tests/fixtures/logs/php/db.log');
        $this->assertTrue($this->stringIsContainedInAnother($expected, $actual));
    }

}