<?php

namespace Tests\Integration;

use Udacity\Database;

trait TestsLoaderTrait {

    protected Database $database;

    protected function loadEnv() {
        $this->resetSuperGlobals();
        $this->setTestingEnv();        
        $this->database = new Database();
        $this->database->writeQuery('TRUNCATE udacity_sl_automation.sessionlead');
        foreach (['/etc/msmtprc', '/etc/msmtprc.test'] as $file) {
            file_put_contents($file, file_get_contents('/var/www/scripts/msmtp/msmtprc.template'));
        }
        if (!defined('APP_MODE')) {
            define('APP_MODE', 'web');
        }
        $this->setDefaultHTTPVerb();
    }

    protected function resetSuperGlobals(): void {
        $_ENV = $_FILES = $_GET = $_POST = $_SESSION = $_SERVER = [];
    }

    protected function setDefaultHTTPVerb(string $verb = 'GET'): void {
        $_SERVER['REQUEST_METHOD'] = $verb;
    }

    protected function setTestingEnv(): void {
        $_ENV['IS_TESTING'] = true;
        $_ENV['DB_HOST'] = 'mariadbtest';
        $_ENV['DB_PASSWORD'] = '';
        $_ENV['DB_PORT'] = 3306;
        $_ENV['DB_USER'] = 'root';
    }

}