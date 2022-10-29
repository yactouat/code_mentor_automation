<?php

namespace Udacity\Apps\CLI;

use Symfony\Component\Console\Application;
use Udacity\Apps\App;
use Udacity\Apps\CLI\Commands\SendEmailsToBehindStudentsCommand;
use Udacity\Apps\CLI\Commands\SendTrainingEndingEmailsCommand;

final class CLIApp extends App {

    private Application $app;

    public function __construct(string $rootDir)
    {
        parent::__construct($rootDir);
        $this->app = new Application("Udacity Session Lead Automation");
        $this->_registerCommands();
    }

    private function _registerCommands(): void {
        // ! if commands properties need to be set at Command class level, check out https://symfony.com/doc/current/console.html#configuring-the-command
        // ! if options are in order, check out https://symfony.com/doc/current/console/input.html#using-command-options
        // ! to ask questions to the user, check out https://symfony.com/doc/current/components/console/helpers/questionhelper.html 
        // ! to get confirmation from the user, check out https://symfony.com/doc/current/components/console/helpers/questionhelper.html#asking-the-user-for-confirmation
        $this->app->add(
            (new SendEmailsToBehindStudentsCommand())
            ->setNewLogger($this->rootDir . "/data/logs/php/cli.log")
        );
        $this->app->add(
            (new SendTrainingEndingEmailsCommand())
            ->setNewLogger($this->rootDir . "/data/logs/php/cli.log")
        );
    }

    public function run(): int {
        return $this->app->run();
    }

}