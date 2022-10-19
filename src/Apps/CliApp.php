<?php

namespace Udacity\Apps;

use Symfony\Component\Console\Application;
use Udacity\Commands\SendEmailsToBehindStudentsCommand;
use Udacity\Commands\SendTrainingEndingEmailsCommand;

// TODO remove code related to SQLite
// TODO test that env is loaded with required keys on app' startup
// TODO validate that MariaDB data structure is up before anything else
final class CliApp extends App {

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