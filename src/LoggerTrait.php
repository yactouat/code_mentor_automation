<?php

namespace App;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

trait LoggerTrait {

    protected Logger $logger;

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

}