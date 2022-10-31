<?php

namespace Udacity;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Udacity\Exceptions\WritePermissionException;

/**
 * this trait shares the logic of logging stuff throughout the app'
 */
trait LoggerTrait {

    protected Logger $logger;

    /**
     * the end time of a logging time span
     *
     * @var float
     */
    protected float $endTime;

    /**
     * the start time of a logging time span
     *
     * @var float
     */
    protected float $startTime;

    /**
     * ends a logging time span and writes the result in the logs
     *
     * @param string|null $logText
     * @return void
     */
    public function endTimer(?string $logText = null) {
        if (isset($this->logger)) {
            $this->endTime = microtime(true);
            $this->logger->info(
                (is_null($logText) ? "it took " : $logText) . (round($this->endTime - $this->startTime, 2)) . "seconds"
            );
        }
    }

    /**
     * returns the location of the PHP logs directory
     *
     * @throws WritePermissionException if the logs directory is not writable
     * 
     * @return string
     */
    public function getLogsDir(): string {
        $logsDir = empty($_ENV['IS_TESTING']) ? '/var/www/data/logs/php/' : '/var/www/tests/fixtures/logs/php/';
        if (!is_writable($logsDir)) {
            throw new WritePermissionException();
        }
        return $logsDir;
    }

    /**
     * sets the instance logger with an already configured logger
     *
     * @param Logger $logger
     * @return void
     */
    public function setLogger(Logger $logger) {
        $this->logger = $logger;
        return $this;
    }

    /**
     * sets the instance logger from scratch
     *
     * @param string $logsPath - the filename to write logs to 
     * @return void
     */
    public function setNewLogger(string $logsPath) {
        $logger = new Logger($logsPath);
        $logger->pushHandler(new StreamHandler($logsPath));
        $this->logger = $logger;
        return $this;
    }

    /**
     * starts a logging time span
     *
     * @return void
     */
    public function startTimer() {
        if (isset($this->logger)) {
            $this->startTime = microtime(true);
        }
    }

}