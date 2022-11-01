<?php

namespace Udacity\Exceptions;

/**
 * Exception thrown when there is no connectivity to a database
 */
final class NoDBConnException extends Exception {

    const MESSAGE = 'no database connectivity';

    public function __construct() {
        parent::__construct(self::MESSAGE, 1);
    }

}