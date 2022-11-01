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
use Udacity\Traits\AuthTrait;
use Udacity\Automations\BehindStudentsEmailAutomation;
use Udacity\Csvs\CsvExtractor;
use Udacity\Emails\Mailer;
use Udacity\Exceptions\AllowedLanguageException;
use Udacity\Exceptions\MsmtprcNotSetException;
use Udacity\Exceptions\NonExistingFileException;
use Udacity\Intl;
use Udacity\Models\SessionLeadModel;

/**
 * this class is responsible for handling CLI input of sending emails to behind students
 * 
 */
#[AsCommand(name: 'emails:behind-students')]
final class SendEmailsToBehindStudentsCommand extends Command
{

    use AuthTrait;

    const COMMAND_HELP = 'This command allows you to send emails in bulk to students that are behind in their Nanodegree program using a Udacity session report CSV file.';
    const CSV_ARG = 'csv';
    const LANG_ARG = 'language';

    /**
     * CLI interface of sending emails to students who are behind on their nanodegree program
     * 
     * after having authenticated, parses the input Udacity students CSV and language and sends the emails
     *
     * @param InputInterface $input - Symfony CLI input class
     * @param OutputInterface $output - Symfony CLI output class
     * @return integer - whether the operation was a success or a failure
     * 
     * @throws LogicException
     * @throws MsmtprcNotSetException
     * @throws NonExistingFileException
     * @throws AllowedLanguageException
     * @throws InvalidArgumentException
     * 
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        if (!$output instanceof ConsoleOutputInterface) {
            throw new \LogicException('This command accepts only an instance of "ConsoleOutputInterface".');
        }

        // authenticating the user
        $qHelper = $this->getHelper('question');
        $userHasAccountQ = new ConfirmationQuestion('Hello there, do you have a Udacity SL Automation account ?');
        $userHasAccount = $qHelper->ask($input, $output, $userHasAccountQ);
        $authFailed = false;
        $email = '';
        if ($userHasAccount) {
            $emailQ = new Question('What is your email ? ', '');
            $passQ = new Question('What is your user password or passphrase ? ', '');
            $email = $qHelper->ask($input, $output, $emailQ);
            $pass = $qHelper->ask($input, $output, $passQ);
            if (!$this->logUserIn($email, $pass)) {
                $authFailed = true;
            }
        } else { // user has no account
            $output->writeln([
                '',
                'let\'s get your account set up...',
                '====================================',
                ''
            ]);
            $emailQ = new Question('Please enter your email ', '');
            $firstNameQ = new Question('Please enter your first name ', '');
            $gAppPassQ = new Question('Please enter your Google App Password ', '');
            $usrPassQ = new Question('Please choose a password or a passphrase ', '');
            $email = $qHelper->ask($input, $output, $emailQ);
            $firstName = $qHelper->ask($input, $output, $firstNameQ);
            $output->writeln([
                '',
                'the next step requires that you have a Gmail account and a Google application password setup...',
                '====================================',
                ''
            ]);
            $output->writeln([
                '',
                'to know how to get a Google account password => https://github.com/yactouat/udacity_sl_automation#sending-emails-in-bulk-to-students',
                ''
            ]);
            $gAppPass = $qHelper->ask($input, $output, $gAppPassQ);
            $usrPass = $qHelper->ask($input, $output, $usrPassQ);
            $validationErrors = SessionLeadModel::validateInputFields([
                'email' => $email,
                'first_name' => $firstName,
                'google_app_password' => $gAppPass,
                'user_passphrase' => $usrPass
            ]);
            if (count($validationErrors) > 0) {
                $authFailed = true;
                foreach ($validationErrors as $error) {
                    $output->writeln([
                        '',
                        $error,
                        ''
                    ]);
                }
            } else {
                $usr = new SessionLeadModel(
                    $email,
                    $firstName,
                    $gAppPass,
                    $usrPass
                );
                $usr->persist();
            }
        }
        if ($authFailed) {
            $output->writeln('<error>authentication error: bad creds</error>');
            return Command::FAILURE;
        }
        $_ENV['authed'] = true;
        $_ENV['authed_user_email'] = $email;

        // retrieving the input
        $csv = $input->getArgument(self::CSV_ARG);
        $language = $input->getArgument(self::LANG_ARG);

        // validation rounds
        $output->writeln('');
        try {
            Mailer::checkMsmtprc();
        } catch (MsmtprcNotSetException $mnse) {
            $output->writeln(
                '<error>emailing conf: '
                .$mnse->getMessage()
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
        } catch (NonExistingFileException $nefe) {
            $output->writeln(
                '<error>'
                .self::CSV_ARG
                .': '.$input->getArgument(self::CSV_ARG)
                .' - '
                .$nefe->getMessage()
                .'</error>'
            );
            $output->writeln('');
            return Command::FAILURE;
        }
        try {
            Intl::languageIsAllowed($language);
        } catch (AllowedLanguageException $ale) {
            $output->writeln(
                '<error>'
                .self::LANG_ARG
                .': '.$input->getArgument(self::LANG_ARG)
                .' - '
                .$ale->getMessage()
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
        $process = new BehindStudentsEmailAutomation();
        $process->runCsv($csv, $language);

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

    /**
     * sets the helps text and the required parameters of this CLI commands
     * 
     * @return void
     */
    protected function configure(): void
    {
        $this
            // the command help shown when running the command with the "--help" option
            ->setHelp(self::COMMAND_HELP)
            ->addArgument(self::CSV_ARG, InputArgument::REQUIRED, 'Path to a valid session report existing CSV file.')
            ->addArgument(self::LANG_ARG, InputArgument::REQUIRED, 'fr OR en')
        ;
    }

}