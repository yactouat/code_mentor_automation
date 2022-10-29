<?php

namespace Udacity\Apps\Web\Controllers;

/**
 * Web Controller interface
 * 
 * mainly used for polymorphism
 */
interface ControllerInterface {

    /**
     * gets the HTTP status code of the current controller instance
     *
     * @return integer
     */
    public function getStatusCode(): int;
}