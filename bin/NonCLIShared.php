<?php

// setting root dir
$rootDir = dirname(__DIR__);

// loading deps
require_once $rootDir."/vendor/autoload.php";
use App\Csv\CsvExtractor;
use App\Emailing\Mailer;
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

    /**
     * runs common validations used in non CLI scripts
     * 
     * - is emailing conf set ?
     * - are `csv` and `language` parameters provided ?
     * - does the input CSV exist ?
     * - is the input language allowed ?
     *
     * @param string|null $csv
     * @param string|null $language
     * 
     * @throws Exception if one of the validations fail
     * 
     * @return void
     */
    public static function runCommonValidationRounds(?string $csv = null, ?string $language = null): void {
        try {
            Mailer::checkMsmtprc();
        } catch (\Exception $e) {
            echo PHP_EOL.$e->getMessage().PHP_EOL;
            echo PHP_EOL."check out how to configure emailing @ https://github.com/yactouat/udacity_sl_automation#sending-emails-in-bulk-to-students".PHP_EOL;
            exit(1);
        }
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