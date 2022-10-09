<?php declare(strict_types=1);

namespace App\Processes;

use App\CsvExtractor;
use App\Emailing\Emails;
use App\Emailing\Mailer;
use App\Models\OnlineResourceModel;

/**
 * this class represents the business logic behind sending all students a cheering up email before the end of the training
 */
final class TrainingEndingEmailProcess {

    /**
     * implements `TrainingEndingEmailProcess` business logic
     *
     * @param string $csv
     * @param string $language
     * @return void
     */
    public static function run(
        string $csv, 
        string $language, 
        ?string $onlineResources = null
    ): void 
    {
        try {
            if (!is_null($onlineResources)) {
                $onlineResources = CsvExtractor::getCSVData(
                    $onlineResources, 
                    OnlineResourceModel::getFields()
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
        foreach ($studentsCoordinates as $student) {
            Mailer::sendEmail(
                $student["Email"],
                $subject,
                Emails::getTrainingEndingEmailFormatted($language, $student["First Name"], $student["Last Name"], $onlineResources)
            );
            echo PHP_EOL."sent email ".$count." out of ".count($studentsCoordinates).PHP_EOL;
            $count++;
        }
    }

}