<?php

namespace Udacity\Apps\Web\Controllers\Resource;

use Udacity\Apps\Web\Controllers\Controller;
use Udacity\Apps\Web\WebApp;
use Udacity\Models\SessionLeadModel;

/**
 * this controller is responsible for handling requests related to the main user of this app: the session lead
 */
final class SessionLeadsController extends Controller implements ResourceControllerInterface {

    /**
     * path to the Twig template of the signup form
     *
     * @var string
     */
    private static string $createTemplatePath = 'session-leads/create.html.twig';

    /**
     * {@inheritDoc}
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * {@inheritDoc}
     */    
    public function create(): string
    {
        return $this->getRenderer()->render(self::$createTemplatePath);
    }
 
    /**
     * shows the relevant page for the session lead based on his/hers auth status
     * 
     * @return string the built login or session lead home page
     */    
    public function index(): string
    {
        $this->setAuthedStatusCode(200);
        return $this->getRenderer()->render($this->getAuthedTwigTemplate(self::$homeTemplatePath));
    }

    /**
     * logs a session lead in
     * 
     * uses PHP sessions for auth state
     *
     * @return string
     */
    public function login(): string
    {
        if(!$this->isAuthed()) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' 
                && isset($_POST['submit']) 
                && isset($_POST['email']) 
                && isset($_POST['user_passphrase']) 
            ) {
                $usr = SessionLeadModel::getEmptyInstance()->selectOneByEmail($_POST['email']);
                $_SESSION['authed'] = count($usr) > 0 && password_verify(
                    $_POST['user_passphrase'],
                    $usr['user_passphrase']
                );
                $_SESSION['authed_first_name'] = $this->isAuthed() ? $usr['first_name'] : '';
                $statusCode = $this->isAuthed() ? 200 : 401;
                $this->setStatusCode($statusCode);
            }
        }
        return $this->isAuthed() ? $this->index() : 
            $this->getRenderer()->render(self::$loginTemplatePath);
    }

    /**
     * logs a session lead out then renders the login page
     *
     * @return string
     */
    public function logout(): string {
        WebApp::resetSession();
        return $this->login();
    }

    /**
     * {@inheritDoc}
     */
    public function persist(): string
    {
        $errors = SessionLeadModel::validateInputFields($_POST);
        if (!isset($_POST['submit'])) {
            $errors[] = '?????? Please send a valid form using the `submit` button';
        }
        if (count($errors) > 0) {
            $this->setStatusCode(400);
            $this->setTwigTemplate(self::$createTemplatePath);
            $this->setTwigData([
                'errors' => $errors,
                'userInput' => $_POST
            ]);
        } else {
            $data = [
                'email' => $_POST['email'],
                'first_name' => $_POST['first_name'],
                'google_app_password' =>$_POST['google_app_password'],
                'user_passphrase' =>$_POST['user_passphrase']
            ];
            (new SessionLeadModel(
                $data['email'],
                $data['first_name'],
                $data['google_app_password'],
                $data['user_passphrase']
            ))->persist();
            $_SESSION['authed'] = true;
            $_SESSION['authed_first_name'] = $data['first_name'];
            $this->setStatusCode(201);
            $this->setTwigTemplate(self::$homeTemplatePath);
        }
        return $this->getRenderer()->render($this->getTwigTemplate(), $this->getTwigData());
    }

}