#!/usr/bin/env php
<?php
/**
 * the CLI that centralizes all of the features of the app'
 * 
 */

// setting root dir
$rootDir = dirname(__DIR__);

// loading deps
require_once $rootDir."/vendor/autoload.php";
use Symfony\Component\Console\Application;

$application = new Application();

// TODO ... register possible commands using the `add` method

$application->run();