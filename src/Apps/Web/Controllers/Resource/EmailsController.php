<?php

namespace Udacity\Apps\Web\Controllers\Resource;

use Udacity\Apps\Web\Controllers\Controller;

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
        return empty($showLoginForm) ? $this->getRenderer()->render('emails/behind-students.create.html.twig') : 
            $showLoginForm;
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