<?php
/**
 * automation responsible for sending emails to cheer students to continue at their upcoming training ending 
 * 
 * - given an existing CSV path of a session report generated from the Udacity session lead dashboard,
 * and the session lead has a Gmail account
 * and the session lead has created a `docker/msmtprc` file and filled it with the relevant values,
 * and the dockerized application stack is running,
 * - when the session lead runs `docker exec -t udacity_sl_automation-php-1 bash -c " php ./bin/behind_students_email.php csv_path en_or_fr"`,
 * - then a templated email cheering up each student to continue his/hers efforts is sent to him/her
 * ! if you tweak this script, make sure that the students emails never leak for privacy reasons
 * - param1 => string $argv[1] first param' passed to the CLI script, must be a path to a valid session report existing CSV file
 * - param2 => string $argv[2] the language in which the email is sent, possible values are:
 *                        - `en`
 *                        - `fr`
 * - param3 => string $argv[3] (optional) the path to a CSV containing online resources containing `Name,Description,URL` fields
 * - this script will send actual emails !
 * 
 */

require_once "./bin/NonCLIShared.php";
use App\CsvExtractor;
use App\Emailing\Emails;
use App\Emailing\Mailer;
use App\Models\OnlineResourceModel;

// parsing command line arguments
$csv = $argv[1] ?? null;
$language = $argv[2] ?? null;
$onlineResources = $argv[3] ?? null;

// validation rounds
NonCLIShared::runCommonValidationRounds($csv, $language);
try {
    if (!is_null($onlineResources)) {
        $onlineResources = CsvExtractor::getCSVData($onlineResources, OnlineResourceModel::getFields());
    }
} catch (\Throwable $th) {
    $onlineResources = null;
}

// get all students coordinates (first and last name, email)
$studentsCoordinates = CsvExtractor::getAllStudentsCoordinates($csv);
$subject = $language == "fr" ? "La fin de notre formation Udacity approche !": "The end of our Udacity training session is near !";

// sending emails loop
$count = 1;
foreach ($studentsCoordinates as $student) {
    Mailer::sendEmail(
        $student["Email"],
        $subject,
        Emails::getTrainingEndingEmailFormatted($language, $student["First Name"], $student["Last Name"], $onlineResources)
    );
    echo PHP_EOL."sent email ".$count." out of ".count($studentsCoordinates).PHP_EOL;
    $count++;
}

exit(0);