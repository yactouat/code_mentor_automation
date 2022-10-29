<?php

namespace Udacity\Apps\CLI\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Udacity\Csvs\CsvExtractor;
use Udacity\Emails\Mailer;
use Udacity\Intl;
use Udacity\LoggerTrait;
use Udacity\Processes\BehindStudentsEmailProcess;

/**
 * this class is responsible for handling CLI input of sending emails to behind students
 * 
 */
#[AsCommand(name: 'emails:behind-students')]
final class SendEmailsToBehindStudentsCommand extends Command
{

    use LoggerTrait;

    const CSV_ARG = 'csv';
    const LANG_ARG = 'language';

    protected static $defaultDescription = 'Sends emails in bulk to students who are behind on their Nanodegree program.';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        if (!$output instanceof ConsoleOutputInterface) {
            throw new \LogicException('This command accepts only an instance of "ConsoleOutputInterface".');
        }

        // authenticating the user
        // $qHelper = $this->getHelper('question');
        // $userHasAccountQ = new ConfirmationQuestion('Do you have a Udacity SL Automation account ?');
        // $userHasAccount = $qHelper->ask($input, $output, $userHasAccountQ);
        // if ($userHasAccount) {
        //     $emailQ = new Question('What is your email ?', '');
        //     $passQ = new Question('What is your user password or passphrase ?', '');
        //     $email = $qHelper->ask($input, $output, $emailQ);
        //     $pass = $qHelper->ask($input, $output, $emailQ);
        // }

        // retrieving the input
        $csv = $input->getArgument(self::CSV_ARG);
        $language = $input->getArgument(self::LANG_ARG);

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
        // EO validation rounds

        // starting to send emails
        $output->writeln([
            '',
            'sending emails to behind students...',
            '====================================',
            ''
        ]);
        $process = ((new BehindStudentsEmailProcess())->setLogger($this->logger));
        $process->run($csv, $language);

        if (count($process->getErrors()) > 0) {
            $output->writeln([
                '',
                '==========================================',
                'some behind students emails were not successfully sent !',
                ''
            ]);
            foreach ($process->getErrors() as $error) {
                $output->writeln([
                    $error,
                ]);
            }
            return Command::FAILURE;
        } else {
            $output->writeln([
                '',
                '==========================================',
                'behind students emails successfully sent !',
                ''
            ]);
            return Command::SUCCESS;
        }
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