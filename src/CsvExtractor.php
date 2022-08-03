<?php

namespace App;

final class CsvExtractor
{

    public static function getCodeCSVRepr(string $csvPath): array {
        // `str_getcsv` parses CSV string into an array => // `str_getcsv` parses a string into an array =>
        // `file` returns an array containing one entry per line in the file
        $csv = array_map('str_getcsv', file($csvPath));
        // transforming the output CSV repr by comining the header row for each item
        array_walk($csv, function(&$line) use ($csv) {
            $line = array_combine($csv[0], $line);
        });
        // removing the CSV header
        array_shift($csv);
        return $csv;
    }

    public static function getBehindStudentsCoordinates(string $csvPath): array {
        $sessionData = self::getCodeCSVRepr($csvPath);
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