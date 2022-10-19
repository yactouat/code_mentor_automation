<?php

namespace Tests;

use Dotenv\Dotenv;
use Udacity\Database;

trait EnvLoaderTrait {

    protected Database $database;

    protected function loadEnv() {
        $_ENV["isTesting"] = true;
        $dotenv = Dotenv::createImmutable('/var/www/tests/fixtures');
        $dotenv->load();
        $this->database = new Database();
    }

}