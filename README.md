# Udacity session lead automation

<!-- TOC -->

- [Udacity session lead automation](#udacity-session-lead-automation)
    - [What is this ?](#what-is-this-)
    - [How to use](#how-to-use)
        - [Prerequisites](#prerequisites)
        - [Access to all automations](#access-to-all-automations)
        - [Sending emails in bulk to students](#sending-emails-in-bulk-to-students)
            - [who are behind on their Nanodegree program](#who-are-behind-on-their-nanodegree-program)
                - [using the CLI](#using-the-cli)
                - [using the dedicated PHP script](#using-the-dedicated-php-script)
            - [to cheer them up when their Nanodegree program ending approaches](#to-cheer-them-up-when-their-nanodegree-program-ending-approaches)
                - [using the CLI](#using-the-cli)
                - [using the dedicated PHP script](#using-the-dedicated-php-script)
    - [Tests and Documentation](#tests-and-documentation)
        - [pre commit hook](#pre-commit-hook)
        - [Tests](#tests)
        - [Documentation](#documentation)
            - [PHP code](#php-code)
                - [consult the docs](#consult-the-docs)
                - [generate the docs](#generate-the-docs)

<!-- /TOC -->

## What is this ?

As a [Udacity Full Stack Nanodegree session lead](https://www.udacity.com/course/full-stack-web-developer-nanodegree--nd0044),
I felt the need to automate a few tasks to gain more efficiency and focus more on the great experience that is teaching and learning with other students !

CLI and all automations live in the `bin` directory of the project.

Automations currently enabled are:

- sending emails in bulk to students who are behind on their Nanodegree program
- sending emails in bulk to all students to cheer them up when the end of a training session is near

The project is starting so it has rough edges, but it's functional !

## How to use

### Prerequisites

- have a working Docker standard installation
- have a Gmail email address
- have PHP Composer installed on your machine and registered in your `PATH`
- before running any feature or test, run a `composer install --ignore-platform-reqs` on your host machine
- ⚠️ then `docker compose up`, otherwise I cant guarantee how the app' will behave

<!-- TODO guidelines related to SQLite3 -->

Specific guidelines by automation are listed below. They assume the application stack is running.

### Access to all automations

- list all automations => `docker exec -it udacity_sl_automation-php-1 bash -c "php ./bin/cli.php list --short"`

### Sending emails in bulk to students

- copy the contents of the `docker/msmtprc.example` to a `docker/msmtprc` file, **HEADS UP** if you copy it somewhere else don't forget to gitignore it as you dont want anybody on the Internet to send emails on your behalf ;)
- create an app' password for your Google account, you can find guidelines on how to do so in <https://dev.to/yactouat/send-gmail-emails-from-a-dockerized-php-app-the-easy-and-free-way-4jn7>
- use the newly created password to update your `docker/msmtprc`, also update this file with your actual gmail account address
- fill the attendance on your latest session on your Udacity mentor dashboard
- generate the CSV report on the same dashboard
- put this report wherever you like (for instance in the `data` folder, that already has its content git ignored)
- ⚠️ IMPORTANT: you need to change the templates in Emails.php to replace all `Yacine` values by your first name in `src/Emails.php`
- you can then tweak the email templates to your liking furthermore

#### ...who are behind on their Nanodegree program

##### using the CLI

- `docker exec -it udacity_sl_automation-php-1 bash -c "php ./bin/cli.php emails:behind-students csv_path en_or_fr"`

##### using the dedicated PHP script

- `docker exec -t udacity_sl_automation-php-1 bash -c "php ./bin/behind_students_email.php csv_path en_or_fr"`

#### ...to cheer them up when their Nanodegree program ending approaches

##### using the CLI

- `docker exec -it udacity_sl_automation-php-1 bash -c "php ./bin/cli.php emails:training-ending csv_path en_or_fr"`
- with additional online resources => `docker exec -it udacity_sl_automation-php-1 bash -c "php ./bin/cli.php emails:training-ending csv_path en_or_fr resources_csv_path"`

##### using the dedicated PHP script

- `docker exec -t udacity_sl_automation-php-1 bash -c "php ./bin/training_ending_email.php csv_path en_or_fr"`
- with additional online resources => `docker exec -t udacity_sl_automation-php-1 bash -c "php ./bin/training_ending_email.php csv_path en_or_fr resources_csv_path"`

## Tests and Documentation

### pre commit hook

- if you want to tweak the `hooks/pre-commit` file to your own needs, remember to re run a `composer install --ignore-platform-reqs` so your changes are taken into effect OR you can copy the hook in the `./git/hooks` and make it executable
- all tests are run in a pre-commit hook that is copied into the `.git/hooks` folder after you ran a `composer install` to install the dependencies, to run this hook, the Docker application stack must be up
- the documentation is also generated in the pre commit hook

### Tests

- it's preferable to have the application stack up and running (`docker compose up`) before running tests (so we are sure that the environment remains the same) => `docker exec -t udacity_sl_automation-php-1 bash -c "/udacity_sl_automation/vendor/bin/phpunit /udacity_sl_automation/tests --colors --testdox"`
- you can also open a terminal in the PHP container and run `./vendor/bin/phpunit tests`
- moreover there is a `tests/fixtures/csv/integration-test-session-report.csv` file (which contains my email, so feel free to replace that) if you want to be sure the real thing actually works as expected when it comes to sending emails
- also, you have test online resources located in `tests/fixtures/csv/online-resources.csv`

### Documentation

#### PHP code

##### consult the docs

- API documentation is available @ <https://yactouat.github.io/udacity_sl_automation/>

##### generate the docs

- we use [phpDocumentor](https://www.phpdoc.org/) and it's [PHAR executable](https://phpdoc.org/phpDocumentor.phar)
- make sure you have downloaded the PHAR provided in the link above
- to generate the documentation, just run => `php phpDocumentor.phar`
- to get a feel at how to write doc blocks, check out => <https://docs.phpdoc.org/3.0/guide/getting-started/what-is-a-docblock.html>, <https://docs.phpdoc.org/3.0/guide/guides/docblocks.html>, and <https://docs.phpdoc.org/3.0/guide/guides/types.html>
- the documentation configuration is described in `phpdoc.dist.xml`
- GitHub Pages is set up at repo level, so changes to the API docs should be reflected online after a few minutes
