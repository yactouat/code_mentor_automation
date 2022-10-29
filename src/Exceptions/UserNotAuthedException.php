<?php

namespace Udacity\Exceptions;

/**
 * Exception thrown when a user is not authenticated
 */
final class UserNotAuthedException extends Exception {

    public function __construct() {
        parent::__construct('user not authenticated');
    }

}