<?php

namespace Udacity;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

trait LoggerTrait {

    protected Logger $logger;

    protected float $endTime;
    protected float $startTime;

    public function endTimer() {
        $this->endTime = microtime(true);
        $this->logger->info("it took " . (round($this->endTime - $this->startTime, 2)) . "seconds");
    }

    public function setLogger(Logger $logger) {
        $this->logger = $logger;
        return $this;
    }

    public function setNewLogger(string $logsPath) {
        $logger = new Logger($logsPath);
        $logger->pushHandler(new StreamHandler($logsPath));
        $this->logger = $logger;
        return $this;
    }

    public function startTimer() {
        $this->startTime = microtime(true);
    }

}