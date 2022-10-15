<?php

// setting root dir
$rootDir = dirname(__DIR__);

// loading deps
require_once $rootDir."/vendor/autoload.php";
use Udacity\App\WebApp;

$app = new WebApp();

// TODO get request route
var_dump($_SERVER['REQUEST_URI']);