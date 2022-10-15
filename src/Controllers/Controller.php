<?php

namespace Udacity\Controllers;

use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

abstract class Controller {

    private Environment $renderer;
    
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

}