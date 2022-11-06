<?php

namespace Tests;

use Udacity\Apps\Web\Controllers\Resource\SessionLeadsController;
use Udacity\Models\SessionLeadModel;
use Udacity\Services\AppModeService;
use Udacity\Services\DatabaseService;
use Udacity\Services\LoggerService;
use Udacity\Services\ServicesContainer;

trait TestsHelperTrait {

    protected function authenticate(string $email = 'test@gmail.com', string $firstName = 'Yacine'): void {
        DatabaseService::getService('write_db')->writeQuery('TRUNCATE udacity_sl_automation.sessionlead');
        $sessionLead = new SessionLeadModel($email, $firstName, 'test g app password', 'test user password');
        $sessionLead->persist();
        $ctlr = new SessionLeadsController();
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [
            'submit' => '1',
            'email' => $email,
            'user_passphrase' => 'test user password',
        ];
        $ctlr->login();
        $_POST = []; // resetting the $_POST array after login
        $_SERVER['REQUEST_METHOD'] = 'GET'; // resetting the server request method after login
    }

    protected function loadEnv(?string $mode = 'web') {
        foreach (['/etc/msmtprc', '/etc/msmtprc.test'] as $file) {
            file_put_contents($file, file_get_contents('/var/www/scripts/msmtp/msmtprc.template'));
        }
        $this->resetSuperGlobals();
        $this->setDefaultHTTPVerb();
        $this->setPHPSelf();
        $this->setTestingEnv();
        ServicesContainer::resetServices();
        // initializing write connection to clear session leads table
        $writeDb = DatabaseService::getService('test_write_db');
        $writeDb->{'writeQuery'}('TRUNCATE udacity_sl_automation.sessionlead');
        $this->nullifyDB();
        ServicesContainer::resetServices();
        // setting app' mode as it is a requirement on all apps (CLI, web, etc.) startup
        AppModeService::getService('app_mode')->{'setMode'}($mode);
    }

    protected function nullifyDB() {
        DatabaseService::getService('test_write_db')->{'closeConn'}();
        DatabaseService::getService('test_read_db')->{'closeConn'}();
    }

    protected function resetLogsFiles(): void {
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

    protected function removeSpacesFromString(string $input): string {
        return str_replace([' ', "\n", "\t"], ['', '', ''], $input);
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
        LoggerService::getService('test_' . $mode . '_logger')
            ->{'setNewLogger'}(
                LoggerService::getService('test_' . $mode . '_logger')->{'getLogsDir'}() . "$mode.log"
            );
        LoggerService::getService('test_db_logger')
            ->{'setNewLogger'}(
                LoggerService::getService('test_db_logger')->{'getLogsDir'}() . 'db.log'
            );
    }

    protected function setPHPSelf(?string $mode = 'web'): void {
        $_SERVER['PHP_SELF'] = $mode === 'web' ? '/var/www/public/index.php' : '/var/www/bin/index.php';
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

    protected function stringIsContainedInAnother(string $expected, string $actual): bool {
        return str_contains($this->removeSpacesFromString($actual), $this->removeSpacesFromString($expected));
    }

    protected function stringsHaveSameContent(string $expected, string $actual): bool {
        return $this->removeSpacesFromString($expected) === $this->removeSpacesFromString($actual);
    }

}