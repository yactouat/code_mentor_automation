<?php

namespace Udacity\App;

final class WebApp {

    private string $inputRoute;
    private int $statusCode;

    public static function getRegisteredRoutes(): array {
        return [
            '/' => ['HomeController', 'index']
        ];
    }

    private function _setStatusCode(): void {
        if (self::getRegisteredRoutes()[$this->inputRoute] ?? false === false) {
            $this->statusCode = 404;
        } else {
            $this->statusCode = 0;
        }
    }

    public function getStatusCode(): int {
        return $this->statusCode;
    }

    public function handleRequest(string $inputRoute): void {
        $this->inputRoute = self::parseRequestRoute($inputRoute);
        $this->_setStatusCode();
    }

    public static function parseRequestRoute(string $inputRoute): string {
        return $inputRoute != '/' ? trim(parse_url($inputRoute, PHP_URL_PATH), '/') : '/';
    }

}