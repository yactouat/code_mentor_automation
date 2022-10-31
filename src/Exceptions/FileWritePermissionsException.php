<?php

namespace Udacity\Exceptions;

/**
 * Exception thrown when the `msmtprc` file is not set in the system
 */
final class FileWritePermissionException extends Exception {

    public function __construct() {
        parent::__construct('no write permissions on this file', 1);
    }

}