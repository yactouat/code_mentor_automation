<?php

namespace Udacity\Apps\CLI;

use Symfony\Component\Console\Application;
use Udacity\Apps\App;
use Udacity\Apps\CLI\Commands\SendEmailsToBehindStudentsCommand;
use Udacity\Apps\CLI\Commands\SendTrainingEndingEmailsCommand;
use Udacity\LoggerTrait;

/**
 * entry point of the (Symfony) CLI app'
 * 
 * sets an instance of the Udacity automation app', registers commands, runs the CLI
 */
final class CLIApp extends App {

    use LoggerTrait;

    /**
     * the application that holds all of the features
     *
     * @var Application
     */
    private Application $app;

    /**
     * constructs an instance of the CLI
     *
     * @param string $rootDir - used by the parent class to set the env
     */
    public function __construct(string $rootDir)
    {
        parent::__construct($rootDir, 'cli');
        $this->app = new Application("Udacity Session Lead Automation");
        $this->_registerCommands();
    }

    /**
     * registers the CLI commands of the app'
     *
     * @return void
     */
    private function _registerCommands(): void {
        // ! if commands properties need to be set at Command class level, check out https://symfony.com/doc/current/console.html#configuring-the-command
        // ! if options are in order, check out https://symfony.com/doc/current/console/input.html#using-command-options
        // ! to ask questions to the user, check out https://symfony.com/doc/current/components/console/helpers/questionhelper.html 
        // ! to get confirmation from the user, check out https://symfony.com/doc/current/components/console/helpers/questionhelper.html#asking-the-user-for-confirmation
        $this->app->add(
            (new SendEmailsToBehindStudentsCommand())->setNewLogger($this->getLogsDir() . "cli.log")
        );
        $this->app->add(
            (new SendTrainingEndingEmailsCommand())->setNewLogger($this->getLogsDir() . "cli.log")
        );
    }

    /**
     * runs the CLI
     *
     * @return integer - whether the I/O was a success or a failure
     */
    public function run(): int {
        return $this->app->run();
    }

}