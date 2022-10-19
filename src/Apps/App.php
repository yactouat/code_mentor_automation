<?php

namespace Udacity\Apps;

use Dotenv\Dotenv;

abstract class App {

    public function __construct(protected string $rootDir)
    {
        $dotenv = Dotenv::createImmutable($this->rootDir);
        // TODO test behavior when keys do not exist
        $dotenv->required([
            'DB_HOST', 
            'DB_PASSWORD', 
            'DB_PORT',
            'DB_USER' 
        ]);
        $dotenv->load();
    }

}