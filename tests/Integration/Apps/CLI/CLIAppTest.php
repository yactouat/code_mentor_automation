<?php declare(strict_types=1);

namespace Tests\Integration\Apps\Web;

use PHPUnit\Framework\TestCase;
use Tests\Traits\TestsLoaderTrait;
use Udacity\Apps\CLI\CLIApp;
use Udacity\Exceptions\NoDBConnException;
use Udacity\Services\LoggerService;

final class CLIAppTest extends TestCase {

    use TestsLoaderTrait;

    protected function setUp(): void
    {
        $this->loadEnv();
        $_SERVER['PHP_SELF'] = '/var/www/bin/index.php';
    }

    public function testConstructWithNoDbConnThrows() {
        $this->resetWithBadDbHost();
        $this->expectException(NoDBConnException::class);
        $this->expectExceptionMessage('no database connectivity');
        $actual = new CLIApp('/var/www');
    }

    public function testConstructSetsLoggerService() {
        $expected = LoggerService::class;
        $app = new CLIApp('/var/www');
        $actual = LoggerService::getAppInstanceLogger();
        $this->assertInstanceOf($expected, $actual);
    }

    public function testConstructSetsCorrrectLoggerServiceName() {
        $expected = 'test_cli_logger';
        $app = new CLIApp('/var/www');
        $actual = LoggerService::getAppInstanceLoggerName();
        $this->assertEquals($expected, $actual);
    }
    // TODO test that logger service has correct name on construct
    // TODO same tests with no db conn

}

