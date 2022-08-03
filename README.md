# Udacity session lead automation

## What is this ?

As a [Udacity Full Stack Nanodegree session lead](https://www.udacity.com/course/full-stack-web-developer-nanodegree--nd0044), 
I felt the need to automate a few tasks to gain more efficiency and focus more on the great experience that is teaching and learning with other students !
Automations currently enabled are:

- sending emails in bulk to students who are behing on their Nanodegree program

## Tests

- all tests are run in a pre-commit hook that is copied into the `.git/hooks` folder after you ran a `composer install` to install the dependencies, to run this hook, the Docker application stack must be up
- during development, if you want to run tests; just open a terminal in the PHP container and run `./vendor/bin/phpunit tests`
