<?php

namespace Udacity\Apps\Web;

use Udacity\Apps\App;
use Udacity\Apps\Web\Controllers\ControllerInterface;
use Udacity\Apps\Web\Controllers\NotFoundController;
use Udacity\LoggerTrait;

/**
 * this class represents the main entry point of the web application
 */
final class WebApp extends App {

    use LoggerTrait;

    /**
     * the controller that is set after parsing the client request
     *
     * @var ControllerInterface
     */
    private ControllerInterface $controller;

    /**
     * the HTTP status code that is set after parsing the client request
     *
     * @var integer
     */
    private int $statusCode;

    /**
     * the client's request route
     *
     * @var string
     */
    private string $inputRoute;

    /**
     * the response that is set after parsing the client request
     *
     * @var string
     */    
    private string $responseOutput;

    /**
     * sets the output after parsing the client's request
     *
     * @return void
     */
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

    /**
     * gets the set controller for this request
     *
     * @return ControllerInterface
     */
    public function getController(): ControllerInterface {
        return $this->controller;
    }

    /**
     * gets the string response when needing to output it to the client
     *
     * @return string
     */
    public function getResponseOutput(): string {
        return $this->responseOutput;
    }
    
    /**
     * gets the set HTTP status code after having parsed the client's request
     *
     * @return integer
     */
    public function getStatusCode(): int {
        return $this->statusCode;
    }

    /**
     * parses the client's request
     * 
     * - gets the requested route
     * - sets the response afterwards
     *
     * @param string $inputRoute
     * @return void
     */
    public function handleRequest(string $inputRoute): void {
        $this->startTimer();
        $this->inputRoute = self::parseRequestRoute($inputRoute);
        $this->_setResponseOutput();
        $this->endTimer("web request processing took : ");
    }

    /**
     * gets the client's requested route
     *
     * @param string $inputRoute
     * @return string
     */
    public static function parseRequestRoute(string $inputRoute): string {
        return $inputRoute != '/' ? trim(parse_url($inputRoute, PHP_URL_PATH), '/') : '/';
    }

}