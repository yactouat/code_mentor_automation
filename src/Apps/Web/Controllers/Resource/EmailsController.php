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
     * 
     * ! not implemented
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * {@inheritDoc}
     * 
     * - is auth protected
     * - checks if provided email type via query string exists otherwise 404's
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
     */
    public function persist(): string
    {
        $showLoginForm = $this->showLoginFormIfNotAuthed();
        $template = empty($showLoginForm) ? self::$notFoundTemplatePath : self::$loginTemplatePath;
        $statusCode = empty($showLoginForm) ? 404 : 401;
        // TODO test unauthed
        // TODO test when uploaded file > `upload_max_filesize`
        // TODO test that we accept only one uploaded file at a time
        // TODO test that uploaded file is a CSV (MIME types and actual extensions)
        // TODO test file name length
        // TODO test uploaded file is stored in correct location
        // TODO test behavior on moving uploading file error
        // TODO test input language
        // TODO test that emails were indeed sent
        $this->setStatusCode($statusCode);
        return $this->getRenderer()->render($template);
    }

}