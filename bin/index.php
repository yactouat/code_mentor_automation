#!/usr/bin/env php
<?php
/**
 * the CLI that centralizes all of the automations of the app'
 * 
 */
// loading deps
require_once dirname(__DIR__) . "/vendor/autoload.php";
use Udacity\Apps\CLI\CLIApp;

$application = new CLIApp(dirname(__DIR__));

$application->runCsv();