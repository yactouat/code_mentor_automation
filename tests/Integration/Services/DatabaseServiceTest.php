<?php declare(strict_types=1);

namespace Tests\Integration\Services;

use Udacity\Services\DatabaseService;
use PHPUnit\Framework\TestCase;
use Tests\TestsHelperTrait;
use Udacity\Exceptions\NoDBConnException;
use Udacity\Services\AppModeService;
use Udacity\Services\LoggerService;
use Udacity\Services\ServicesContainer;

final class DatabaseServiceTest extends TestCase {

    use TestsHelperTrait;

    public function testDbNameIsAsExpected() {
        $expected = 'udacity_sl_automation';
        $this->assertEquals($expected, DatabaseService::$dbName);
    }

    public function testGetServiceReturnsInstanceOfDbService() {
        $expected = DatabaseService::class;
        $this->resetSuperGlobals();
        $this->setTestingEnv();
        $logger = LoggerService::getService('test_db_logger');
        $logger->{'setNewLogger'}($logger->{'getLogsDir'}() . "db.log");
        $actual = DatabaseService::getService('test_read_db');
        $this->assertInstanceOf($expected, $actual);
    }

    public function testGetServiceWithNoDBConnectivityThrowsCorrectException() {
        $this->resetWithBadDbHost();
        $this->expectException(NoDBConnException::class);
        $this->expectExceptionMessage('no database connectivity');
        $database = DatabaseService::getService('test_read_db');
        var_dump($database);
    }

}