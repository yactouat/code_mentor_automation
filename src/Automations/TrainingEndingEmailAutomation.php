<?php declare(strict_types=1);

namespace Udacity\Automations;

use Udacity\Csvs\StudentsCsvExtractor as CsvExtractor;
use Udacity\Emails\Emails;
use Udacity\Emails\Mailer;
use Udacity\Models\OnlineResourceModel;
use Udacity\Services\LoggerService;

/**
 * this class represents the business logic behind sending all students a cheering up email before the end of the training
 */
final class TrainingEndingEmailAutomation extends Automation {

    /**
     * implements `TrainingEndingEmailAutomation` business logic
     *
     * @param string $csv
     * @param string $language
     * @return void
     */
    public function runFromCsv(
        string $csv, 
        string $language, 
        ?string $onlineResources = null
    ): void 
    {
        try {
            if (!is_null($onlineResources)) {
                $onlineResources = CsvExtractor::getCSVData(
                    $onlineResources, 
                    OnlineResourceModel::getCsvFields()
                );
            }
        } catch (\Throwable $th) {
            $onlineResources = null;
        }
        // get all students coordinates (first and last name, email)
        $studentsCoordinates = CsvExtractor::getAllStudentsCoordinates($csv);
        $subject = $language == "fr" ? "La fin de notre formation Udacity approche !"
            : "The end of our Udacity training session is near !";
        // sending emails loop
        $count = 1;
        $logger = LoggerService::getAppInstanceLogger();
        $logger->{'startTimer'}();
        foreach ($studentsCoordinates as $student) {
            Mailer::sendEmail(
                $student["Email"],
                $subject,
                Emails::getTrainingEndingEmailFormatted($language, $student["First Name"], $student["Last Name"], $onlineResources)
            );
            $logger->{'info'}("sent training ending email ".$count." out of ".count($studentsCoordinates));
            $count++;
        }
        $logger->{'endTimer'}("sending emails took : ");
    }

}