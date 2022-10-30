<?php declare(strict_types=1);

namespace Udacity\Automations;

use Udacity\Csvs\StudentsCsvExtractor as CsvExtractor;
use Udacity\Emails\Emails;
use Udacity\Emails\Mailer;
use Udacity\Exceptions\EmailNotDeliveredException;
use Udacity\LoggerTrait;

/**
 * this class represents the business logic behind sending students behind emails
 */
final class BehindStudentsEmailAutomation extends Automation {

    use LoggerTrait;

    /**
     * implements `BehindStudentsEmailAutomation` business logic
     *
     * @param string $csv
     * @param string $language
     * @return void
     * 
     */
    public function run(string $csv, string $language): void {
        // get students who are behind coordinates (first and last name, email)
        $behindStudentsCoordinates = CsvExtractor::getBehindStudentsCoordinates($csv);
        $subject = $language == "fr" ? "Session Connect Udacity": "Udacity Connect session";
        // sending emails loop
        $count = 1;
        $this->startTimer();
        foreach ($behindStudentsCoordinates as $student) {
            try {
                Mailer::sendEmail(
                    $student["Email"],
                    $subject,
                    Emails::getBehindStudentEmailFormatted($language, $student["First Name"], $student["Last Name"])
                );
                $this->logger->info("sent behind student email ".$count." out of ".count($behindStudentsCoordinates));
            } catch (EmailNotDeliveredException $ende) {
                $this->errors[] = "email not sent to " . $student["Email"];
            } finally {
                $count++;
            }
        }
        $this->endTimer("sending emails took : ");
    }

}