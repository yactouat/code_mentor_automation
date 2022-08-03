<?php

namespace App;

final class CsvExtractor
{

    public static function getCodeCSVRepr(string $csvPath): array {
        $csv = array_map('str_getcsv', file($csvPath));
        array_walk($csv, function(&$a) use ($csv) {
            $a = array_combine($csv[0], $a);
        });
        array_shift($csv); # remove column header
        return $csv;
    }

}