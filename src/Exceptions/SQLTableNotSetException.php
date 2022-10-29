<?php

namespace Udacity\Exceptions;

/**
 * Exception thrown when a SQL table that is supposed to exist does not exist
 */
final class SQLTableNotSetException extends Exception {

    public function __construct() {
        parent::__construct('the required SQL table does not exist');
    }

}