<?php

namespace Udacity\Apps;

use Dotenv\Dotenv;
use Udacity\Exceptions\BadEnvException;

/**
 * parent class of all apps (CLI, Web)
 * 
 * this is where the app' environment is loaded
 */
abstract class App {

    /**
     * constructs an instance of the app' with its environment
     *
     * @param string $rootDir - where to find the `.env` file
     * 
     * @throws BadEnvException
     */
    public function __construct(protected string $rootDir = '/var/www')
    {
        if (empty($_ENV['IS_TESTING'])) {
            $dotenv = Dotenv::createImmutable($this->rootDir, '.env');
            $dotenv->safeLoad();
        }
        foreach ([
            'DB_HOST', 
            'DB_PASSWORD', 
            'DB_PORT',  
            'DB_USER' 
        ] as $key) {
            if (!isset($_ENV[$key])) {
                throw new BadEnvException();
            }
        }
    }

}