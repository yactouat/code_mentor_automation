<?php

namespace Udacity\Apps\Web\Controllers;

final class NotFoundController extends Controller implements ControllerInterface {

    public function __construct()
    {
        parent::__construct();
    }

    public function index(): string
    {
        $this->setStatusCode(404);
        return $this->getRenderer()->render("not-found.html.twig");
    }
    
}