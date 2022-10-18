<?php declare(strict_types=1);

namespace Udacity\Csvs;

/**
 * class responsible for extracting data from CSVs
 * 
 */
class CsvExtractor
{

    /**
     * checks if an input CSV exists, throws Exception if not
     *
     * @param string $inputCsvPath
     *
     * @throws Exception with message 'Please provide an existing input CSV'
     * 
     * @return void
     */
    public static function checkFileExistence(string $inputCsvPath): void {
        if (!file_exists($inputCsvPath) || pathinfo($inputCsvPath, PATHINFO_EXTENSION) != "csv") {
            throw new \Exception('Please provide an existing input CSV', 1);
        }
    }

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
        self::checkFileExistence($inputCsvPath);
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

}