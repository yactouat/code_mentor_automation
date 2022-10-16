<?php

namespace Udacity\Controllers;

final class HomeController extends Controller implements ControllerInterface {

    public function __construct()
    {
        parent::__construct();
    }

    public function create(): string
    {
        return '';
    }
    
    public function index(): string
    {
        return $this->getRenderer()->render("home.html.twig");
    }

}