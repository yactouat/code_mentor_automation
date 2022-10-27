<?php

namespace Udacity\Apps\Web;

use Udacity\Apps\App;
use Udacity\Apps\Web\Controllers\ControllerInterface;
use Udacity\Apps\Web\Controllers\NotFoundController;
use Udacity\LoggerTrait;

final class WebApp extends App {

    use LoggerTrait;

    private ControllerInterface $controller;
    private int $statusCode;
    private string $inputRoute;
    private string $responseOutput;

    private function _setResponseOutput(): void {
        $parsedRoute = Routes::getRegisteredRoutes()[$_SERVER['REQUEST_METHOD']][$this->inputRoute] ?? false;
        if (!$parsedRoute) {
            $this->controller = new NotFoundController();
            $this->responseOutput = $this->controller->index();
        } else {
            $controllerClass = 'Udacity\Apps\Web\Controllers\\' . $parsedRoute[0];
            $this->controller = new $controllerClass();
            $this->responseOutput = $this->controller->{$parsedRoute[1]}();
        }
        $this->statusCode = $this->controller->getStatusCode();
    }

    public function getController(): ControllerInterface {
        return $this->controller;
    }

    public function getResponseOutput(): string {
        return $this->responseOutput;
    }
    
    public function getStatusCode(): int {
        return $this->statusCode;
    }

    public function handleRequest(string $inputRoute): void {
        $this->startTimer();
        $this->inputRoute = self::parseRequestRoute($inputRoute);
        $this->_setResponseOutput();
        $this->endTimer("web request processing took : ");
    }

    public static function parseRequestRoute(string $inputRoute): string {
        return $inputRoute != '/' ? trim(parse_url($inputRoute, PHP_URL_PATH), '/') : '/';
    }

}