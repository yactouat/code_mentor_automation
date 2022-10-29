<?php

namespace Udacity\Apps\Web\Controllers\Resource;

use Udacity\Apps\Web\Controllers\ControllerInterface;

/**
 * this inteface defines what a resource controller should do
 */
interface ResourceControllerInterface extends ControllerInterface {

    /**
     * shows the web form to create an instance of the related resource
     *
     * @return string
     */
    public function create(): string;

    /**
     * shows the main view related to the linked resource
     *
     * @return string
     */
    public function index(): string;

    /**
     * saves data related to the linked resource
     * 
     * this includes database records, personal config files, etc.
     *
     * @return string
     */
    public function persist(): string;

}