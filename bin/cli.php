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
use App\Commands\SendTrainingEndingEmailsCommand;
use Symfony\Component\Console\Application;

$application = new Application();

// ! if commands properties need to be set at Command class level, check out https://symfony.com/doc/current/console.html#configuring-the-command
// ! if options are in order, check out https://symfony.com/doc/current/console/input.html#using-command-options
// ! to ask questions to the user, check out https://symfony.com/doc/current/components/console/helpers/questionhelper.html 
$application->add((new SendEmailsToBehindStudentsCommand())->setNewLogger($rootDir . "/data/logs/cli.log"));
$application->add((new SendTrainingEndingEmailsCommand())->setNewLogger($rootDir . "/data/logs/cli.log"));

$application->run();