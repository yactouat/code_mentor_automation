<?php

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