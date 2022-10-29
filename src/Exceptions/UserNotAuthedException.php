<?php

namespace Udacity\Exceptions;

/**
 * Exception thrown when an email fails to be delivered using the `mail` function
 */
final class UserNotAuthedException extends Exception {

    public function __construct() {
        parent::__construct('user not authenticated');
    }

}