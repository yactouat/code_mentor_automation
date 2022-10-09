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

use App\Commands\SendEmailsToBehindStudentsCommand;
use Symfony\Component\Console\Application;

$application = new Application();

// ! if commands properties need to be set at Command class level, check out https://symfony.com/doc/current/console.html#configuring-the-command
$application->add(new SendEmailsToBehindStudentsCommand());

$application->run();