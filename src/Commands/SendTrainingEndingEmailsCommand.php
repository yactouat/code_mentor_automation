<?php

namespace App\Commands;

use App\CsvExtractor;
use App\Emailing\Mailer;
use App\Intl;
use App\Processes\TrainingEndingEmailProcess;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * this class is responsible for handling CLI input of sending all students a cheering up email before the end of the training
 * 
 */
#[AsCommand(name: 'emails:training-ending')]
class SendTrainingEndingEmailsCommand extends Command
{

    const CSV_ARG = 'csv';
    const LANG_ARG = 'language';
    const ONLINE_RESOURCES = 'online-resources';

    protected static $defaultDescription = 'Sends cheering up emails in bulk to all before the end of the Udacity training.';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        if (!$output instanceof ConsoleOutputInterface) {
            throw new \LogicException('This command accepts only an instance of "ConsoleOutputInterface".');
        }

        // retrieving the input
        $csv = $input->getArgument(self::CSV_ARG);
        $language = $input->getArgument(self::LANG_ARG);
        $onlineResources = null;

        // validation rounds
        $output->writeln('');
        try {
            Mailer::checkMsmtprc();
        } catch (\Exception $e) {
            $output->writeln(
                '<error>emailing conf: '
                .$e->getMessage()
                .'</error>'
            );
            $output->writeln([
                '',
                '====================================',
                ''
            ]);
            $output->writeln('<href=https://github.com/yactouat/udacity_sl_automation#sending-emails-in-bulk-to-students>learn how to configure emailing here</>');
            $output->writeln('');
            return Command::FAILURE;
        }
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
            $output->writeln('');
            return Command::FAILURE;
        }
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
            $output->writeln('');
            return Command::FAILURE;
        }
        try {
            $onlineResources = $input->getArgument(self::ONLINE_RESOURCES);
        } catch (InvalidArgumentException $iae) {
            $output->writeln([
                '',
                'skipping online resources content...',
                '=============================================',
                ''
            ]);
        }
        // EO validation rounds

        // starting to send emails
        $output->writeln([
            '',
            'sending cheering up emails to all students...',
            '=============================================',
            ''
        ]);
        TrainingEndingEmailProcess::run($csv, $language, $onlineResources);

        // feedback to user
        $output->writeln([
            '',
            '=============================================',
            'cheering up emails emails successfully sent !',
            ''
        ]);

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this
            // the command help shown when running the command with the "--help" option
            ->setHelp('This command allows you to send emails in bulk to students that are behind in their Nanodegree program using a Udacity session report CSV file.')
            ->addArgument(self::CSV_ARG, InputArgument::REQUIRED, 'Path to a valid session report existing CSV file.')
            ->addArgument(self::LANG_ARG, InputArgument::REQUIRED, 'fr OR en')
            ->addArgument(self::ONLINE_RESOURCES, InputArgument::OPTIONAL, 'Path to a CSV containing online resources containing `Name,Description,URL` fields.')
        ;
    }

}