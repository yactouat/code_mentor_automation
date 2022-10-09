<?php

namespace App;

use App\Models\StudentModel;

/**
 * class responsible for extracting data from session reports CSVs
 * 
 */
final class CsvExtractor
{

    /**
     * gets an array-like representation of a CSV file data
     *
     * @param string $inputCsvPath must be a path to a valid existing CSV file
     * @param array $expectedFields list array describing the expected fields in the input CSV
     * 
     * @return array[] returns an array containing the formatted CSV data as in => 
     *               `[
     *                   [
     *                       "item_1_key" => "item_1_val",
     *                   ]
     *                   [
     *                       "item_2_key" => "item_2_val",
     *                   ]
     *                ]`
     */
    public static function getCSVData(string $inputCsvPath, array $expectedFields): array {
        // `str_getcsv` parses CSV string into an array => // `str_getcsv` parses a string into an array =>
        // `file` returns an array containing one entry per line in the file
        $csv = array_map('str_getcsv', file($inputCsvPath));
        if (count(array_intersect($csv[0], $expectedFields)) < count($expectedFields)) {
            throw new \Exception('Please provide a valid input CSV', 1);
        }
        // transforming the output CSV repr by combining the header row for each item
        array_walk($csv, function(&$line) use ($csv) {
            $line = array_combine($csv[0], $line);
        });
        // removing the CSV header
        array_shift($csv);
        return $csv;
    }

        
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