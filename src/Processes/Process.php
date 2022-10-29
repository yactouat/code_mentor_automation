<?php declare(strict_types=1);

namespace Udacity\Processes;

abstract class Process {
    protected $errors = [];

    public function getErrors(): array {
        return $this->errors;
    }
}