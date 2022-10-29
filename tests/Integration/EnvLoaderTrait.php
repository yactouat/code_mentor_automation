<?php

namespace Tests\Integration;

use Dotenv\Dotenv;
use Udacity\Database;

trait EnvLoaderTrait {

    protected Database $database;

    protected function loadEnv(?string $envDir = null) {
        if (!defined('APP_MODE')) {
            define('APP_MODE', 'web');
        }
        $_ENV["isTesting"] = true;
        $dotenv = Dotenv::createImmutable(
            is_null($envDir) ? '/var/www/tests/fixtures' : $envDir
        );
        $dotenv->load();
        $this->database = new Database();
        if (\file_exists('/etc/msmptrc.test')) {
            unlink('/etc/msmptrc.test');
        }
    }

}