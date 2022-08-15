<?php
/**
 * automation responsible for sending emails in bulk to students who are behind on their Nanodegree program
 * 
 * - given an existing CSV path of a session report generated from the Udacity session lead dashboard,
 * and the session lead has a Gmail account
 * and the session lead has created a `docker/msmtprc` file and filled it with the relevant values,
 * and the dockerized application stack is running,
 * - when the session lead runs `docker exec -t udacity_sd_automation-php-1 bash -c " php ./bin/email_behind_students.php csv_path en_or_fr"`,
 * - then a templated email reminding the student of his duty to show up at his Connect sessions is sent to him
 * 
 * @param string $argv[1] first param' passed to the CLI script, must be a path to a valid session report existing CSV file
 * @param string $argv[2] the language in which the email is sent, possible values are:
 *                        - `en`
 *                        - `fr`
 * 
 * @return void this script will send actual emails !
 */

// setting root dir
$rootDir = dirname(__DIR__);

// loading deps
require_once $rootDir."/vendor/autoload.php";
use App\CsvExtractor;
use App\Emails;
use App\Mailer;

// parsing command line arguments
$csv = $argv[1] ?? null;
$language = $argv[2] ?? null;

// validation rounds
if (is_null($csv) || is_null($language)) {
    echo PHP_EOL."this script requires the path to the CSV as the first arg and the language (en or fr) as the second arg".PHP_EOL;
    exit(1);
}
if (!file_exists($csv) || pathinfo($csv, PATHINFO_EXTENSION) != "csv") {
    echo PHP_EOL."wrong input csv".PHP_EOL;
    exit(1);
}
if (!in_array($language, ["en", "fr"])) {
    echo PHP_EOL."allowed languages are en or fr".PHP_EOL;
    exit(1);
}

// get students who are behind coordinates (first and last name, email)
$behindStudentsCoordinates = CsvExtractor::getBehindStudentsCoordinates($csv);
$subject = $language == "fr" ? "Session Connect Udacity": "Udacity Connect session";

// sending emails loop
$count = 0;
foreach ($behindStudentsCoordinates as $student) {
    Mailer::sendEmail(
        $student["Email"],
        $subject,
        Emails::getBehindStudentEmailFormatted($language, $student["First Name"], $student["Last Name"])
    );
    echo PHP_EOL."sent email ".$count." out of ".count($behindStudentsCoordinates);
}

exit(0);