<?php

namespace Udacity\Apps\Web\Controllers;

/**
 * controller responsible for showing an output when a server error happens on a client request
 */
final class ServerErrorController extends Controller implements ControllerInterface {

    /**
     * constructor
     * 
     * server error page HTTP status code is always 500
     */
    public function __construct()
    {
        parent::__construct();
        $this->setStatusCode(500);
    }

    /**
     * builds the server error HTML page
     *
     * @return string
     */
    public function index(): string
    {
        return $this->getRenderer()->render("server-error.html.twig");
    }
    
}