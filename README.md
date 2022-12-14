# Udacity session lead automation

<!-- TOC -->

- [Udacity session lead automation](#udacity-session-lead-automation)
    - [What is this ?](#what-is-this-)
    - [How to use](#how-to-use)
        - [Prerequisites](#prerequisites)
            - [Prerequisites for use](#prerequisites-for-use)
                - [Sending emails in bulk to students](#sending-emails-in-bulk-to-students)
            - [Prerequisites for dev](#prerequisites-for-dev)
        - [Run the app'](#run-the-app)
            - [web UI](#web-ui)
            - [CLI commands](#cli-commands)
                - [send emails to students who are behind on their Nanodegree program](#send-emails-to-students-who-are-behind-on-their-nanodegree-program)
                - [send emails to students to cheer them up as their Nanodegree program ending approaches](#send-emails-to-students-to-cheer-them-up-as-their-nanodegree-program-ending-approaches)
    - [Debug the app'](#debug-the-app)
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
I felt the need to automate a few tasks to gain more efficiency and focus more on the great experience that is teaching and learning with other students ! ⚠️ This is not an official Udacity project, just a tool to help me do my job better.

There are always 2 ways of running the automations:

- the CLI, accessible from the `bin` directory
- the web application UI, accessible from the `public` directory

Currently enabled automations are:

- sending emails in bulk to students who are behind on their Nanodegree program
- sending emails in bulk to all students to cheer them up when the end of a training session is near

The project is just getting started so it has a few rough edges, but it's functional !

## How to use

### Prerequisites

#### Prerequisites for use

- have a working Docker standard installation
- have a Gmail email address
- create an app' password for your Google account, you can find guidelines on how to do so in <https://dev.to/yactouat/send-gmail-emails-from-a-dockerized-php-app-the-easy-and-free-way-4jn7> and store it somewhere safe as the app' will need it
- `docker compose up`

##### Sending emails in bulk to students

- fill the attendance of your latest session on your Udacity mentor dashboard
- generate the CSV report from the same Udacity dashboard
- put this report wherever you like (for instance in the `data/csv` folder, that already has its content git ignored)
- you can then tweak the email templates to your liking furthermore

#### Prerequisites for dev

- have PHP Composer installed on your machine and registered in your `PATH`
- before running any feature or test, run a `composer install --ignore-platform-reqs` on your host machine

### Run the app'

- kill anything that's running on port 80
- `docker compose up`
- then, for web: go to `http://localhost`
- for CLI, you can run `docker exec -it udacity_sl_automation-php-1 bash -c "php /var/www/bin/index.php list --short"` to list all available automations

#### web UI

To use the web UI, just browse it, things should be pretty self-explanatory ;)

#### CLI commands

##### send emails to students who are behind on their Nanodegree program

- `docker exec -it udacity_sl_automation-php-1 bash -c "php /var/www/bin/index.php emails:behind-students csv_path en_or_fr"`

##### send emails to students to cheer them up as their Nanodegree program ending approaches

- `docker exec -it udacity_sl_automation-php-1 bash -c "php /var/www/bin/index.php emails:training-ending csv_path en_or_fr"`
- with additional online resources => `docker exec -it udacity_sl_automation-php-1 bash -c "php /var/www/bin/index.php emails:training-ending csv_path en_or_fr resources_csv_path"`

## Debug the app'

- you need to have the `ms-vscode-remote.remote-containers` extension installed
- in VSCode, open the command palette and execute `Dev Containers: Attach to Running Container`
- select the PHP container
- this will open a new VSCode window in which you can enable debugging features directly in the dockerized env
- you can now open the `/var/www/` folder within this window editor if it's not already open there
- inside this window, hit `ctrl + shift + d` and click on the `Run and Debug` button
- in the debuggers selection, choose the `install extension` option to install the `PHP Debug` extension in the dockerized editor if it's not already installed
- then, you can just click on launch in the debug pane and a new browser tab running the app' in debug mode will be opened for you
- from there on, happy step debugging !

## Tests and Documentation

### pre commit hook

- if you want to tweak the `hooks/pre-commit` file to your own needs, remember to re run a `composer install --ignore-platform-reqs` so your changes are taken into effect OR you can copy the hook in the `./git/hooks` and make it executable
- all tests are run in a pre-commit hook that is copied into the `.git/hooks` folder after you ran a `composer install` to install the dependencies, to run this hook, the Docker application stack must be up
- the documentation is also generated in the pre commit hook and automatically added to your commit

### Tests

- you should have the application stack up and running (`docker compose up`) before running tests (so we are sure that the environment remains the same) => `sh tests.sh`
- you can also open a terminal in the PHP container and run `/var/www/bin/vendor/bin/phpunit tests`
- moreover there is a `./tests/fixtures/csv/integration-test-session-report.csv` file (which contains my email, so feel free to replace that) if you want to be sure the real thing actually works as expected when it comes to sending emails
- also, you have test online resources located in `./tests/fixtures/csv/online-resources.csv`

### Documentation

#### PHP code

##### consult the docs

- API documentation is available @ <https://yactouat.github.io/udacity_sl_automation/> and is updated on each merge to main branch

##### generate the docs

- we use [phpDocumentor](https://www.phpdoc.org/) and it's [PHAR executable](https://phpdoc.org/phpDocumentor.phar)
- make sure you have downloaded the PHAR provided in the link above; if you have run a pre commit hook once, this should have been done automatically
- to generate the documentation, just run => `php phpDocumentor.phar`
- to get a feel at how to write doc blocks, check out => <https://docs.phpdoc.org/3.0/guide/getting-started/what-is-a-docblock.html>, <https://docs.phpdoc.org/3.0/guide/guides/docblocks.html>, and <https://docs.phpdoc.org/3.0/guide/guides/types.html>
- the documentation configuration is described in `phpdoc.dist.xml`
- GitHub Pages is set up at repo level, so changes to the API docs should be reflected online after a few minutes
