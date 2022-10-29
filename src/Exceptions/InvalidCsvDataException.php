<?php

namespace Udacity\Exceptions;

/**
 * Exception thrown when an input CSV does not contain valid data
 */
final class InvalidCsvDataException extends Exception {

    public function __construct() {
        parent::__construct("please provide a valid input CSV", 1);
    }

}