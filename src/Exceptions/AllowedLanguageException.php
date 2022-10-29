<?php

namespace Udacity\Exceptions;

/**
 * Exception thrown when the input language is not allowed in the app'
 */
final class AllowedLanguageException extends Exception {

    public function __construct() {
        parent::__construct('allowed languages are `en` or `fr`');
    }

}