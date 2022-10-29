<?php

namespace Udacity\Apps\Web\Controllers;

use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

/**
 * parent class of all the web app' controllers
 */
abstract class Controller {

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
     * the path to the homepage template
     *
     * @var string
     */
    protected static string $homeTemplatePath = 'home.html.twig';
    
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
     * gets the Twig instance of the controller
     *
     * @return Environment
     */
    public function getRenderer(): Environment {
        return $this->renderer;
    }

    /**
     * @inheritDoc
     */
    public function getStatusCode(): int {
        return $this->statusCode;
    }

    /**
     * sets the HTTP status code of the instance
     * 
     * the controller is responsible for setting the status code before the response output
     *
     * @param integer $statusCode
     * @return void
     */
    protected function setStatusCode(int $statusCode) {
        $this->statusCode = $statusCode;
    }


}