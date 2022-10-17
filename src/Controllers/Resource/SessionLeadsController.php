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

    public function persist(): string
    {
        $errors = [];
        if (!isset($_POST["submit"])) {
            $errors[] = "⚠️ Please send a valid form using the `submit` button";
        }
        if (count($errors) > 0) {
            return $this->getRenderer()->render("session-leads.create.html.twig", [
                "errors" => $errors
            ]);
        }
        // TODO test that no `submit` field in $_POST array returns a 400 status code
        // TODO test that user input is kept on error
        return '';
    }

}