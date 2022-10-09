<?php

// setting root dir
$rootDir = dirname(__DIR__);

// loading deps
require_once $rootDir."/vendor/autoload.php";
use App\CsvExtractor;
use App\Intl;

/**
 * shared code for non-CLI executable automations present in the `./bin` folder
 * 
 * - [`./bin/behind_students_email.php`](files/bin-behind-students-email.html)
 * - [`./bin/training_ending_email.php`](files/bin-training-ending-email.html)
 * 
 * @package bin
 */
final class NonCLIShared {

    public static function runCommonValidationRounds(?string $csv = null, ?string $language = null): void {
        if (is_null($csv) || is_null($language)) {
            echo PHP_EOL."this script requires the path to the CSV as the first arg and the language (en or fr) as the second arg".PHP_EOL;
            exit(1);
        }
        try {
            CsvExtractor::checkFileExistence($csv);
            Intl::languageIsAllowed($language);
        } catch (\Exception $e) {
            echo PHP_EOL.$e->getMessage().PHP_EOL;
            exit(1);
        }        
    }

}