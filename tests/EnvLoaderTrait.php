<?php

namespace Tests;

use Dotenv\Dotenv;
use Udacity\Database;

trait EnvLoaderTrait {

    protected Database $database;

    protected function loadEnv(?string $envDir = null) {
        $_ENV["isTesting"] = true;
        $dotenv = Dotenv::createImmutable(
            is_null($envDir) ? '/var/www/tests/fixtures' : $envDir
        );
        $dotenv->load();
        $this->database = new Database();
    }

}