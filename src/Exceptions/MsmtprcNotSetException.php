<?php

namespace Udacity\Exceptions;

/**
 * Exception thrown when the `msmtprc` file is not set in the system
 */
final class MsmtprcNotSetException extends Exception {

    public function __construct() {
        parent::__construct("`msmptrc` file not configured", 1);
    }

}