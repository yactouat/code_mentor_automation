<?php

namespace App;

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
     * 
     * @return array returns an array containing the formatted CSV data as in => 
     *               `[
     *                   [
     *                       "item_1_key" => "item_1_val",
     *                   ]
     *                   [
     *                       "item_2_key" => "item_2_val",
     *                   ]
     *                ]`
     */
    public static function getCodeCSVRepr(string $inputCsvPath): array {
        // `str_getcsv` parses CSV string into an array => // `str_getcsv` parses a string into an array =>
        // `file` returns an array containing one entry per line in the file
        $csv = array_map('str_getcsv', file($inputCsvPath));
        // transforming the output CSV repr by comining the header row for each item
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
     * @return array an array containing the first and last name of the student who is behind on hers/his Nanodegree track
     */
    public static function getBehindStudentsCoordinates(string $inputCsvPath): array {
        $sessionData = self::getCodeCSVRepr($inputCsvPath);
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

}