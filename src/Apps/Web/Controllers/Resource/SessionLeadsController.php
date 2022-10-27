<?php

namespace Udacity\Apps\Web\Controllers\Resource;

use Udacity\Apps\Web\Controllers\AuthedTrait;
use Udacity\Apps\Web\Controllers\Controller;
use Udacity\Models\SessionLeadModel;

final class SessionLeadsController extends Controller implements ResourceControllerInterface {

    use AuthedTrait;

    public function __construct()
    {
        parent::__construct();
    }

    public function create(): string
    {
        return $this->getRenderer()->render('session-leads.create.html.twig');
    }
    
    public function index(): string
    {
        if(!$this->isAuthed()) {
            $this->setStatusCode(401);
            return $this->login();
        }
        return $this->getRenderer()->render('home.html.twig');
    }

    // TODO login feature
    public function login(): string
    {
        if($this->isAuthed()) {
            return $this->index();
        }
        return $this->getRenderer()->render('session-leads.login.html.twig');
    }

    public function persist(): string
    {
        $errors = SessionLeadModel::validateInputFields($_POST);
        if (count($errors) > 0) {
            $this->setStatusCode(400);
            return $this->getRenderer()->render('session-leads.create.html.twig', [
                'errors' => $errors,
                'userInput' => $_POST
            ]);
        } else {
            $data = [
                'email' => $_POST['email'],
                'first_name' => $_POST['first_name'],
                'google_app_password' => $_POST['google_app_password'],
                'user_passphrase' => $_POST['user_passphrase']
            ];
            (new SessionLeadModel(
                $data['email'],
                $data['first_name'],
                $data['google_app_password'],
                $data['user_passphrase']
            ))->persist();
            $_SESSION['authed'] = true;
            $this->setStatusCode(201);
            return $this->index();
        }
    }

}