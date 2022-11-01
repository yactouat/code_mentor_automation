<?php

namespace Udacity\Apps;

use Dotenv\Dotenv;
use Udacity\Exceptions\BadEnvException;
use Udacity\Services\AppModeService;
use Udacity\Services\DatabaseService;
use Udacity\Services\LoggerService;

/**
 * parent class of all apps (CLI, Web)
 * 
 * this is where the app' environment is loaded
 */
abstract class App {

    /**
     * constructs an instance of the app' with its environment as well as a logger
     *
     * @param string $rootDir - where to find the `.env` file
     * 
     * @throws BadEnvException
     */
    public function __construct(string $rootDir, string $mode)
    {
        switch ($mode) {
            case 'cli':
            case 'web':
                AppModeService::getService('app_mode')->{'setMode'}($mode);
        }
        $this->_loadEnv($rootDir);
        $this->_setLoggerService($mode);
    }

    /**
     * loads app' environment
     *
     * @param string $rootDir
     * @param string $mode
     * 
     * @throws BadEnvException
     * 
     * @return void
     */
    private function _loadEnv(string $rootDir): void {
        if (empty($_ENV['IS_TESTING'])) {
            $dotenv = Dotenv::createImmutable($rootDir, '.env');
            $dotenv->safeLoad();
        }
        foreach ([
            'DB_HOST', 
            'DB_PASSWORD', 
            'DB_PORT',  
            'DB_USER',
            'ROOT_DIR'
        ] as $key) {
            if (!isset($_ENV[$key])) {
                throw new BadEnvException();
            }
        }
    }

    /**
     * sets and configure the single logger service in CLI mode
     *
     * @return void
     * 
     * TODO test that logger instance is set after instanciated both types of apps
     * TODO test that this logger writes at the right places
     */
    private function _setLoggerService(string $mode): void {
        $logger = LoggerService::getService($mode . '_logger');
        $logger->{'setNewLogger'}($logger->{'getLogsDir'}() . "$mode.log");
    }

    /**
     * sets read and write db connections
     * 
     * @throws NoDBConnException
     *
     * @return void
     */
    protected function setDbServices() {
        DatabaseService::getService('read_db');
        DatabaseService::getService('write_db');
    }

}