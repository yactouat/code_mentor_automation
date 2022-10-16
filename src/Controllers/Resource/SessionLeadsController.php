<?php

namespace Udacity\Controllers\Resource;

use Udacity\Controllers\Controller;

final class SessionLeadsController extends Controller implements ResourceControllerInterface {

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

    public function persist(): void
    {
        
    }

}