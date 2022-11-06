<?php

namespace Udacity\Exceptions;

/**
 * Exception thrown when an input CSV does not contain valid data
 */
final class KeyValueArrayExpectedException extends Exception {

    public function __construct() {
        parent::__construct('a key value array was expected', 1);
    }

}