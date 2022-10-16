<?php

namespace Udacity\Controllers;

final class UsersController extends Controller implements ControllerInterface {

    public function __construct()
    {
        parent::__construct();
    }

    public function create(): string
    {
        return $this->getRenderer()->render("session-leads.create.html.twig");
    }
    
    // TODO redirect to /sessions/create if not connected
    public function index(): string
    {
        return $this->getRenderer()->render("home.html.twig");
    }

}