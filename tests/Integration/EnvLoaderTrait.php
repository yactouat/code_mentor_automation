<?php

namespace Tests\Integration;

use Dotenv\Dotenv;
use Udacity\Database;

trait EnvLoaderTrait {

    protected Database $database;

    protected function loadEnv(?string $envDir = null) {
        foreach (['/etc/msmtprc', '/etc/msmtprc.test'] as $file) {
            if (\file_exists($file)) {
                unlink($file);
            }
        }
        if (!defined('APP_MODE')) {
            define('APP_MODE', 'web');
        }
        $_POST = [];
        $_GET = [];
        $_SESSION = [];
        $_FILES = [];
        $_SERVER['REQUEST_METHOD'] = "GET";
        $dotenv = Dotenv::createImmutable(
            is_null($envDir) ? '/var/www/tests/fixtures' : $envDir
        );
        $dotenv->load();
        $_ENV["isTesting"] = true;
        $this->database = new Database();
        $this->database->writeQuery('TRUNCATE udacity_sl_automation.sessionlead');
    }

}