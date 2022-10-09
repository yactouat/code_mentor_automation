<?php declare(strict_types=1);

namespace App\Processes;

use App\CsvExtractor;
use App\Emailing\Emails;
use App\Emailing\Mailer;

/**
 * this class represents the business logic behind sending students behind emails
 */
final class BehindStudentsEmailProcess {

    /**
     * implements `BehindStudentsEmailProcess` business logic
     *
     * @param string $csv
     * @param string $language
     * @return void
     */
    public static function run(string $csv, string $language): void {
        // get students who are behind coordinates (first and last name, email)
        $behindStudentsCoordinates = CsvExtractor::getBehindStudentsCoordinates($csv);
        $subject = $language == "fr" ? "Session Connect Udacity": "Udacity Connect session";
        // sending emails loop
        $count = 1;
        foreach ($behindStudentsCoordinates as $student) {
            Mailer::sendEmail(
                $student["Email"],
                $subject,
                Emails::getBehindStudentEmailFormatted($language, $student["First Name"], $student["Last Name"])
            );
            echo PHP_EOL."sent email ".$count." out of ".count($behindStudentsCoordinates).PHP_EOL;
            $count++;
        }
    }

}