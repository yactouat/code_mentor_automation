<?php

namespace Udacity\Apps;

use Dotenv\Dotenv;

abstract class App {

    public function __construct(protected string $rootDir)
    {
        $dotenv = Dotenv::createImmutable($this->rootDir, '.env');
        $dotenv->load();
        foreach ([
            'DB_HOST', 
            'DB_PASSWORD', 
            'DB_PORT',  
            'DB_USER' 
        ] as $key) {
            if (!isset($_ENV[$key])) {
                throw new \Exception("The app environment is not properly set", 1);
            }
        }
    }

}