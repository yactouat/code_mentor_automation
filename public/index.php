<?php
/**
 * the web UI that centralizes all of the automations of the app'
 * 
 */

// setting root dir
$rootDir = dirname(__DIR__);

// loading deps
require_once $rootDir."/vendor/autoload.php";
use Udacity\Apps\Web\WebApp;

// initializing the app'
$app = new WebApp($rootDir);

// processing the input request
$app->handleRequest($_SERVER["REQUEST_URI"]);

// outputting a response
http_response_code($app->getStatusCode());
echo $app->getResponseOutput();