<?php

namespace Udacity\App;

use Udacity\Controllers\ControllerInterface;
use Udacity\Controllers\NotFoundController;
use Udacity\LoggerTrait;

final class WebApp {

    use LoggerTrait;

    private ControllerInterface $controller;
    private int $statusCode;
    private string $inputRoute;
    private string $responseOutput;

    public static function getRegisteredRoutes(): array {
        return [
            '/' => ['Resource\SessionLeadsController', 'index'],
            'session-leads/create' => ['Resource\SessionLeadsController', 'create'],
            'session-leads' => ['Resource\SessionLeadsController', 'persist']
        ];
    }

    private function _setResponseOutput(): void {
        $parsedRoute = self::getRegisteredRoutes()[$this->inputRoute] ?? false;
        if (!$parsedRoute) {
            $this->controller = new NotFoundController();
            $this->responseOutput = $this->controller->index();
        } else {
            $controllerClass = 'Udacity\Controllers\\' . $parsedRoute[0];
            $this->controller = new $controllerClass();
            $this->responseOutput = $this->controller->{$parsedRoute[1]}();
        }
    }

    private function _setStatusCode(): void {
        if (!isset(self::getRegisteredRoutes()[$this->inputRoute])) {
            $this->statusCode = 404;
        } else {
            $this->statusCode = 200;
        }
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
        $this->_setStatusCode();
        $this->_setResponseOutput();
        $this->endTimer("web request processing took : ");
    }

    public static function parseRequestRoute(string $inputRoute): string {
        return $inputRoute != '/' ? trim(parse_url($inputRoute, PHP_URL_PATH), '/') : '/';
    }

}