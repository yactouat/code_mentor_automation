<?php

namespace Udacity\Apps\Web\Controllers;

use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

abstract class Controller {

    private Environment $renderer;
    private int $statusCode = 200;
    
    protected function __construct()
    {
        $loader = new FilesystemLoader("/var/www/views");
        $this->renderer = new Environment(
            $loader,
            [
                "cache" => false,
                "debug" => true
            ]
        );
        $this->renderer->addExtension(new DebugExtension());
    }

    public function getRenderer(): Environment {
        return $this->renderer;
    }

    public function getStatusCode(): int {
        return $this->statusCode;
    }

    protected function setStatusCode(int $statusCode) {
        $this->statusCode = $statusCode;
    }


}