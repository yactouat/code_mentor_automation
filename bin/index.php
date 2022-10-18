#!/usr/bin/env php
<?php
/**
 * the CLI that centralizes all of the automations of the app'
 * 
 */

// setting root dir
$rootDir = dirname(__DIR__);

// loading deps
require_once $rootDir."/vendor/autoload.php";
use Udacity\Apps\CliApp;

$application = new CliApp($rootDir);

$application->run();