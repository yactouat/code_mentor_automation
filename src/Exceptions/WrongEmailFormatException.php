<?php

namespace Udacity\Exceptions;

/**
 * Exception thrown when the input email has a wrong format
 */
final class WrongEmailFormatException extends Exception {

    public function __construct() {
        parent::__construct('wrong email format');
    }

}