<?php

namespace Tests\Traits;

use Udacity\Services\AppModeService;
use Udacity\Services\DatabaseService;
use Udacity\Services\LoggerService;
use Udacity\Services\ServicesContainer;

trait TestsLoaderTrait {

    protected function loadEnv(?string $mode = 'web') {
        $this->resetSuperGlobals();
        $this->setTestingEnv();
        ServicesContainer::resetServices();
        $this->setLoggersWithMode();
        // initializing read and write connections      
        $readDb = DatabaseService::getService('test_read_db');
        $writeDb = DatabaseService::getService('test_write_db');
        $writeDb->{'writeQuery'}('TRUNCATE udacity_sl_automation.sessionlead');
        foreach (['/etc/msmtprc', '/etc/msmtprc.test'] as $file) {
            file_put_contents($file, file_get_contents('/var/www/scripts/msmtp/msmtprc.template'));
        }
        $this->setDefaultHTTPVerb();
    }

    protected function nullifyDB() {
        DatabaseService::getService('test_write_db')->{'closeConn'}();
        DatabaseService::getService('test_read_db')->{'closeConn'}();
    }

    protected function resetSuperGlobals(): void {
        $_ENV = $_FILES = $_GET = $_POST = $_SESSION = $_SERVER = [];
    }

    protected function resetWithBadDbHost(): void {
        $this->nullifyDB();
        ServicesContainer::resetServices();
        $this->setLoggersWithMode();
        $_ENV['DB_HOST'] = 'unexistinghost';
    }

    protected function setDefaultHTTPVerb(string $verb = 'GET'): void {
        $_SERVER['REQUEST_METHOD'] = $verb;
    }

    protected function setLoggersWithMode(string $mode = 'web') {
        AppModeService::getService('app_mode')->{'setMode'}($mode);
        $appLogger = LoggerService::getService($mode . '_logger');
        $appLogger->{'setNewLogger'}($appLogger->{'getLogsDir'}() . "$mode.log");
        $dbLogger = LoggerService::getService('test_db_logger');
        $dbLogger->{'setNewLogger'}($dbLogger->{'getLogsDir'}() . 'db.log');
    }

    protected function setTestingEnv(string $mode = 'web'): void {
        $_ENV['IS_TESTING'] = true;
        $_ENV['APP_MODE'] = $mode;
        $_ENV['DB_HOST'] = 'mariadbtest';
        $_ENV['DB_PASSWORD'] = '';
        $_ENV['DB_PORT'] = 3306;
        $_ENV['DB_USER'] = 'root';
        $_ENV['ROOT_DIR'] = '/var/www';
    }

}