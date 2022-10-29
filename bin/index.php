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
use Udacity\Apps\CLI\CLIApp;

define('APP_MODE', 'cli');

$application = new CLIApp($rootDir);

$application->run();