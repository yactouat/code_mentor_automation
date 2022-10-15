<?php

namespace Udacity\Controllers;

final class NotFoundController extends Controller implements ControllerInterface {

    public function __construct()
    {
        parent::__construct();
    }

    public function index(): string
    {
        return $this->getRenderer()->render("not-found.html.twig");
    }
    
}