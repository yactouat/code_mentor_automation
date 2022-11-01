<?php

namespace Udacity\Apps\Web\Controllers;

use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Udacity\Traits\AuthTrait;

/**
 * parent class of all the web app' controllers
 */
abstract class Controller {

    use AuthTrait;

    /**
     * the path to the homepage template
     *
     * @var string
     */
    protected static string $homeTemplatePath = 'home.html.twig';

    /**
     * path to the Twig template of the login form
     *
     * @var string
     */    
    protected static string $loginTemplatePath = 'session-leads/login.html.twig';

    /**
     * the path to the not found page template
     *
     * @var string
     */
    protected static string $notFoundTemplatePath = 'not-found.html.twig';

    /**
     * the templating engine used to render the HTML of the app' (Twig)
     *
     * @var Environment
     */
    private Environment $renderer;

    /**
     * the default HTTP status code (200)
     *
     * @var integer
     */
    private int $statusCode = 200;

    /**
     * the Twig context data to pass to the set template for the instance
     *
     * @var array
     */
    private array $twigData = [];
    
    /**
     * the template to output by the controller instance
     *
     * @var string
     */
    private string $twigTemplate;

    /**
     * parent controller constructor
     * 
     * - sets the templating engine
     */
    protected function __construct()
    {
        $loader = new FilesystemLoader('/var/www/views');
        $this->renderer = new Environment(
            $loader,
            [
                'cache' => false,
                'debug' => true
            ]
        );
        $this->renderer->addExtension(new DebugExtension());
    }

    /**
     * returns the login page Twig template path if the user is not authenticated, otherwise returns the intended template
     *
     * @return string
     */
    protected function getAuthedTwigTemplate(string $twigTemplate): string {
        return !$this->isAuthed() ? self::$loginTemplatePath : $twigTemplate;
    }

    /**
     * gets the Twig instance of the controller
     *
     * @return Environment
     */
    public function getRenderer(): Environment {
        return $this->renderer;
    }

    /**
     * {@inheritDoc}
     */
    public function getStatusCode(): int {
        return $this->statusCode;
    }

    /**
     * gets the Twig context data to pass to the set template for the instance
     * 
     * @return array
     */
    protected function getTwigData(): array {
        return $this->twigData;
    }

    /**
     * returns the set Twig template for this instance
     *
     * @return string
     */
    protected function getTwigTemplate(): string {
        return $this->twigTemplate;
    }

    /**
     * sets the relevant status code based on auth 
     *
     * @param integer $statusCode - the intended status code
     * @return void
     */
    protected function setAuthedStatusCode(int $statusCode) : void {
        $this->setStatusCode(!$this->isAuthed() ? 401 : $statusCode);
    }

    /**
     * sets the HTTP status code of the instance
     * 
     * the controller is responsible for setting the status code before the response output
     *
     * @param integer $statusCode
     * @return void
     */
    protected function setStatusCode(int $statusCode): void {
        $this->statusCode = $statusCode;
    }

    /**
     * sets the Twig context data to pass to the set template for the instance 
     *
     * @param array $twigData - the Twig context data
     * @return void
     */
    protected function setTwigData(array $twigData) : void {
        if (!empty($twigData)) {
            $this->twigData = $twigData;
        }
    }

    /**
     * sets the Twig template for the controller instance
     *
     * @param string $twigTemplate - relative path to the Twig template
     * @return void
     */
    protected function setTwigTemplate(string $twigTemplate) : void {
        $this->twigTemplate = $twigTemplate;
    }

}