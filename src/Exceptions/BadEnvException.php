<?php

namespace Udacity\Exceptions;

/**
 * Exception thrown when the environment is not properly set
 */
final class BadEnvException extends Exception {

    public function __construct() {
        parent::__construct('the app environment is not properly set');
    }

}