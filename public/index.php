<?php

// setting root dir
$rootDir = dirname(__DIR__);

// loading deps
require_once $rootDir."/vendor/autoload.php";
use Udacity\App\WebApp;

// initializing the app'
$app = new WebApp();

// processing the input request
$app->handleRequest($_SERVER["REQUEST_URI"]);

// outputting a response
http_response_code($app->getStatusCode());
echo $app->getResponseOutput();