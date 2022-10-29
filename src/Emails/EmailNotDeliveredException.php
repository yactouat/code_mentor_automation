<?php

namespace Udacity\Emails;

use Udacity\Exception;

/**
 * Exception thrown when an email fails to be delivered using the `mail` function
 */
final class EmailNotDeliveredException extends Exception {

    public function __construct() {
        parent::__construct("email failed delivery at send");
    }

}