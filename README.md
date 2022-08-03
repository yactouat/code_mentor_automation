# Udacity session lead automation

## What is this ?

As a [Udacity Full Stack Nanodegree session lead](https://www.udacity.com/course/full-stack-web-developer-nanodegree--nd0044), 
I felt the need to automate a few tasks to gain more efficiency and focus more on the great experience that is teaching and learning with other students !
Automations currently enabled are:

- sending emails in bulk to students who are behind on their Nanodegree program

The project is starting so it has rough edges, but it's functional !

## How to use

### Prerequisites

- have a working Docker standard installation
- have a Gmail email address

### sending emails in bulk to students who are behind on their Nanodegree program

- copy the contents of the `docker/msmtprc.example` to a `docker/msmtprc` file
- create an app' password for your Google account, you can find guidelines on how to do so in https://dev.to/yactouat/send-gmail-emails-from-a-dockerized-php-app-the-easy-and-free-way-4jn7
- use the newly created password to update your `docker/msmtprc`, also update this file with your actual gmail account address
- fill the attendance on your latest session on your Udacity mentor dashboard
- generate the CSV report on the same dashboard
- put this report wherever you like (for instance in the `data` folder, that already has its content git ignored)
- tweak the email templates to your liking in `src/Emails.php`
- `docker compose up`
- `docker exec -t udacity_sd_automation-php-1 bash -c " php ./bin/email_behind_students.php csv_path en_or_fr"`


## Tests

- all tests are run in a pre-commit hook that is copied into the `.git/hooks` folder after you ran a `composer install` to install the dependencies, to run this hook, the Docker application stack must be up
- during development, if you want to run tests; just open a terminal in the PHP container and run `./vendor/bin/phpunit tests`
