<?php

namespace Udacity\Apps\Web\Controllers\Resource;

use Udacity\Apps\Web\Controllers\Controller;
use Udacity\Models\EmailModel;

/**
 * this controller is responsible for handling requests related to sending emails from the web
 */
final class EmailsController extends Controller implements ResourceControllerInterface {

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
        $showLoginForm = $this->showLoginFormIfNotAuthed();
        $template = empty($showLoginForm) ? self::$notFoundTemplatePath : self::$loginTemplatePath;
        $statusCode = empty($showLoginForm) ? 404 : 401;
        if (
            empty($showLoginForm) 
            && !empty($_GET['type']) 
            && in_array($_GET['type'], EmailModel::getValidEmailsTypes())
        ) {
            $template = 'emails/' . $_GET['type'] . '.create.html.twig';
            $statusCode = 200;
        }
        $this->setStatusCode($statusCode);
        return $this->getRenderer()->render($template);
    }
 
    /**
     * ! not implemented
     * 
     * @return string
     */    
    public function index(): string
    {
        return $this->getRenderer()->render(self::$notFoundTemplatePath);
    }

    /**
     * {@inheritDoc}
     * 
     * TODO
     */
    public function persist(): string
    {
        return '';
    }

}