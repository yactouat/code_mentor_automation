<?php

namespace Udacity;

use Exception as GlobalException;

abstract class Exception extends GlobalException {

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}