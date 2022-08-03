<?php declare(strict_types=1);

namespace Tests\Unit;

use App\CsvExtractor;
use PHPUnit\Framework\TestCase;

final class CsvExtractorTest extends TestCase {

    public function testRepresentsValidSessionReportCSVInCode() {
        $csvPath = "./../fixtures/valid-session-report-oneliner.csv";
        $expected = [
            [
                "First Name" => "Test2 FirstName",
                "Last Name" => "Test2 LastName",
                "Email" => "test2@yahoo.fr",
                "Student ID" => "9c9f10ca-e668-11ec-a0dc-cf8fb8494ea5",
                "Session ID" => "4981",
                "Nanodegree Key" => "nd0044-alg-t2-en",
                "On-Track Status" => "Behind",
                "Completed Projects" => '0',
                "Total Projects" => '0',
                "# Present" => '0',
                "# Absent" => '0',
                "# Excused" => '0',
                "% Attendance" => '0',
                "Last Session Attended" => '',
                "Notes" =>  '[]'
            ]
        ];
        $actual = CsvExtractor::getCodeCSVRepr($csvPath);
        $this->assertEquals($expected, $actual);
    }

}

