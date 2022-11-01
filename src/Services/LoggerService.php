<?php

namespace Udacity\Services;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Udacity\Exceptions\WritePermissionException;

/**
 * this trait shares the logic of logging stuff throughout the app'
 * 
 */
final class LoggerService extends ServicesContainer {

    protected ?Logger $logger = null;

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
     * logs statistical data about undesired stateful events happening in the app'
     *
     * TODO test when logger is not set
     * 
     * @return void
     */
    public function critical(string $message): void {
        $this->logger->critical($message);
    }

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
     * gets logger service associated to the current app' instance
     *
     * @return self
     */
    public static function getAppInstanceLogger(): self {
        return self::getService(self::getAppInstanceLoggerName());
    }

    /**
     * gets logger service associated to the current app' instance name
     *
     * @return string
     */
    public static function getAppInstanceLoggerName(): string {
        return AppModeService::getService('app_mode')->{'getMode'}() . '_logger';
    }


    /**
    * @inheritDoc
    */
    public static function getService(string $id): self {
        if(empty(self::$_instances[$id])) {
            self::$_instances[$id] = new self();  
        }
        return self::$_instances[$id];
    }

    /**
     * logs statistical data about the app'
     *
     * TODO test when logger is not set
     * 
     * @return void
     */
    public function info(string $message): void {
        $this->logger->info($message);
    }

    /**
     * logs statistical data about noticeable events happening in the app'
     *
     * TODO test when logger is not set
     * 
     * @return void
     */
    public function notice(string $message): void {
        $this->logger->notice($message);
    }

    /**
     * sets the instance logger with an already configured logger
     *
     * @param Logger $logger
     * @return void
     */
    public function setLogger(Logger $logger) {
        $this->logger = null;
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
        $this->logger = null;
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