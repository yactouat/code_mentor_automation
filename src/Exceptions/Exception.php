<?php

namespace Udacity\Exceptions;

use Exception as GlobalException;

/**
 * parent class of all app's exceptions
 * 
 */
abstract class Exception extends GlobalException{

    /**
     * constructor of all app's exceptions
     *   
     * all app' exceptions have an exit code of 1 by default
     *
     * @param string $message
     * @param int $code
     */
    public function __construct(string $message, int $code = 1) {
        parent::__construct($message, $code);
    }

    /**
     * outputs a string representation of the instance exception
     *
     * @return string
     */
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}