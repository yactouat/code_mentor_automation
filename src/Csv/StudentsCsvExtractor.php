<?php declare(strict_types=1);

namespace Udacity\Csv;

use Udacity\Models\StudentModel;

/**
 * class responsible for extracting data from session reports CSVs
 * 
 */
final class StudentsCsvExtractor extends CsvExtractor
{

    /**
     * extracts students coordinates from a Udacity session report, scoped to students that are behind on their Nanodegree track
     * 
     * ! students coordinates must be kept isolated and secret at all times, this means =>
     * ! - no versioning
     * ! - no email address appearing on an email that is not intended to the given student
     *
     * @param string $inputCsvPath must be a path to a valid existing CSV file
     * 
     * @return array[] an array containing the first and last name of the student who is behind on hers/his Nanodegree track
     */
    public static function getBehindStudentsCoordinates(string $inputCsvPath): array {
        $sessionData = self::getCSVData($inputCsvPath, StudentModel::getFields());
        $formatted = [];
        foreach ($sessionData as $student) {
            if ($student["On-Track Status"] == "Behind") {
                $formatted[] = [
                    "First Name" => $student["First Name"],
                    "Last Name" => $student["Last Name"],
                    "Email" => $student["Email"]
                ];
            }
        }
        return $formatted;
    }

    /**
     * extracts all students coordinates from a Udacity session report
     * 
     * ! students coordinates must be kept isolated and secret at all times, this means =>
     * ! - no versioning
     * ! - no email address appearing on an email that is not intended to the given student
     *
     * @param string $inputCsvPath must be a path to a valid existing CSV file
     * 
     * @return array[] an array containing the first and last name of the Udacity student
     */
    public static function getAllStudentsCoordinates(string $inputCsvPath): array {
        $sessionData = self::getCSVData($inputCsvPath, StudentModel::getFields());
        $formatted = [];
        foreach ($sessionData as $student) {
            $formatted[] = [
                "First Name" => $student["First Name"],
                "Last Name" => $student["Last Name"],
                "Email" => $student["Email"]
            ];
        }
        return $formatted;
    }

}