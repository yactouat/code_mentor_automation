<?php

namespace Udacity\Apps;

use Udacity\Controllers\ControllerInterface;
use Udacity\Controllers\NotFoundController;
use Udacity\LoggerTrait;

// TODO remove code related to SQLite
// TODO test that env is loaded with required keys on app' startup
// TODO validate that MariaDB data structure is up before anything else
final class WebApp extends App {

    use LoggerTrait;

    private ControllerInterface $controller;
    private int $statusCode;
    private string $inputRoute;
    private string $responseOutput;

    public static function getRegisteredRoutes(): array {
        return [
            "GET" => [
                '/' => ['Resource\SessionLeadsController', 'index'],
                'session-leads/create' => ['Resource\SessionLeadsController', 'create']
            ],
            "POST" => [
                'session-leads/create' => ['Resource\SessionLeadsController', 'persist']
            ]
        ];
    }

    private function _setResponseOutput(): void {
        $parsedRoute = self::getRegisteredRoutes()[$_SERVER['REQUEST_METHOD']][$this->inputRoute] ?? false;
        if (!$parsedRoute) {
            $this->controller = new NotFoundController();
            $this->responseOutput = $this->controller->index();
        } else {
            $controllerClass = 'Udacity\Controllers\\' . $parsedRoute[0];
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