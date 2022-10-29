<?php

namespace Udacity\Exceptions;

/**
 * Exception thrown when a file does not exist
 */
final class NonExistingFileException extends Exception {

    public function __construct() {
        parent::__construct('please provide an existing input file');
    }

}