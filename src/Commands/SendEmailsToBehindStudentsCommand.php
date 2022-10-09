<?php

namespace App\Commands;

use App\CsvExtractor;
use App\Intl;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * this class is responsible for handling CLI input of sending emails to behind students
 * 
 */
#[AsCommand(name: 'emails:behind-students')]
class SendEmailsToBehindStudentsCommand extends Command
{

    const CSV_ARG = 'csv';
    const LANG_ARG = 'language';

    protected static $defaultDescription = 'Sends emails in bulk to students who are behind on their Nanodegree program.';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        if (!$output instanceof ConsoleOutputInterface) {
            throw new \LogicException('This command accepts only an instance of "ConsoleOutputInterface".');
        }

        // TODO ... put here the code to send emails to behind students
        // validation rounds
        $csv = $input->getArgument(self::CSV_ARG);
        try {
            CsvExtractor::checkFileExistence($csv);
        } catch (\Exception $e) {
            $output->writeln(
                '<error>'
                .self::CSV_ARG
                .': '.$input->getArgument(self::CSV_ARG)
                .' - '
                .$e->getMessage()
                .'</error>'
            );
            return Command::FAILURE;
        }
        $language = $input->getArgument(self::LANG_ARG);
        try {
            Intl::languageIsAllowed($language);
        } catch (\Exception $e) {
            $output->writeln(
                '<error>'
                .self::LANG_ARG
                .': '.$input->getArgument(self::LANG_ARG)
                .' - '
                .$e->getMessage()
                .'</error>'
            );
            return Command::FAILURE;
        }

        // starting to send emails
        $introSection = $output->section();
        $introSection->writeln([
            "sending emails to behind students...",
            "====================================",
            ''
        ]);

        // TODO link to docs related to msmtp if validation fails
        // ... as in $output->writeln('<href=https://symfony.com>Symfony Homepage</>');

        // TODO `$introSection->clear()` when all emails are sent
        return Command::SUCCESS;

        // TODO return this to indicate incorrect command usage; e.g. invalid options or missing arguments (it's equivalent to returning int(2))
        // return Command::INVALID

    }

    protected function configure(): void
    {
        $this
            // the command help shown when running the command with the "--help" option
            ->setHelp('This command allows you to send emails in bulk to students that are behind in their Nanodegree program using a Udacity session report CSV file.')
            ->addArgument(self::CSV_ARG, InputArgument::REQUIRED, 'Path to a valid session report existing CSV file.')
            ->addArgument(self::LANG_ARG, InputArgument::REQUIRED, 'fr OR en')
        ;
    }

}