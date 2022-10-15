<?php

// setting root dir
$rootDir = dirname(__DIR__);

// loading deps
require_once $rootDir."/vendor/autoload.php";
use Udacity\App\WebApp;

// TODO CLI application class
$app = new WebApp();

echo "it works";