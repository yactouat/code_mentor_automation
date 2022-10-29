<?php declare(strict_types=1);

namespace Udacity\Processes;

abstract class Process {

    protected $errors = [];

    // TODO test that no process using Gmail can be run if no Gmail email configured

    public function getErrors(): array {
        return $this->errors;
    }

}