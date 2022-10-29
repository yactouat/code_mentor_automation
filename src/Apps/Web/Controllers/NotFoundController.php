<?php

namespace Udacity\Apps\Web\Controllers;

/**
 * controller responsible for showing an output when a route is not found in the app'
 */
final class NotFoundController extends Controller implements ControllerInterface {

    /**
     * constructor
     * 
     * not found page HTTP status code is always 404
     */
    public function __construct()
    {
        parent::__construct();
        $this->setStatusCode(404);
    }

    /**
     * builds the not found HTML page
     *
     * @return string
     */
    public function index(): string
    {
        return $this->getRenderer()->render("not-found.html.twig");
    }
    
}