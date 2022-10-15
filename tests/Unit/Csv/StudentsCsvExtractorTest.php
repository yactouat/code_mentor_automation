<?php declare(strict_types=1);

namespace Tests\Unit\Csv;

use App\Csv\StudentsCsvExtractor as CsvExtractor;
use App\Models\StudentModel;
use Exception;
use PHPUnit\Framework\TestCase;

final class StudentsCsvExtractorTest extends TestCase {

    public function testgetCSVData() {
        $inputCsvPath = "./tests/fixtures/csv/valid-session-report-oneliner.csv";
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
        $actual = CsvExtractor::getCSVData($inputCsvPath, StudentModel::getFields());
        $this->assertEquals($expected, $actual);
    }

    public function testGetBehindStudentsCoordinates() {
        $inputCsvPath = "./tests/fixtures/csv/valid-session-report.csv";
        $expected = [
            [
                "First Name" => '',
                "Last Name" => '',
                "Email" => "test@gmail.com"
            ],
            [
                "First Name" => "Test2 FirstName",
                "Last Name" => "Test2 LastName",
                "Email" => "test2@yahoo.fr"
            ],
            [
                "First Name" => "Test5 FirstName",
                "Last Name" => "Test5 LastName",
                "Email" => "test5@gmail.com"
            ],
            [
                "First Name" => "Test8 FirstName",
                "Last Name" => "Test8 LastName",
                "Email" => "test8@gmail.com"
            ],
            [
                "First Name" => "Test9 FirstName",
                "Last Name" => "Test9 LastName",
                "Email" => "test9@gmail.com"
            ]
        ];
        $actual = CsvExtractor::getBehindStudentsCoordinates($inputCsvPath);
        $this->assertEquals($expected, $actual);
    }

    public function testGetCsvDataWithInvalidCSVThrowsInvalidInputCSVException() {
        $inputCsvPath = "./tests/fixtures/csv/invalid-session-report.csv";
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Please provide a valid input CSV");
        $actual = CsvExtractor::getCSVData($inputCsvPath, StudentModel::getFields());
    }

    public function testGetCsvDataWithInvalidStudentsDataThrowsInvalidStudentsDataException() {
        $inputCsvPath = "./tests/fixtures/csv/invalid-session-report-data.csv";
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Please provide a valid input CSV");
        $actual = CsvExtractor::getCSVData($inputCsvPath, StudentModel::getFields());
    }

    public function testGetAllStudentsCoordinates() {
        $inputCsvPath = "./tests/fixtures/csv/valid-session-report.csv";
        $expected = 9;
        $actual = CsvExtractor::getAllStudentsCoordinates($inputCsvPath);
        $this->assertCount($expected, $actual);
    }

    public function testGetCsvDataWithNonExistingCSVThrowsInvalidInputCSVException() {
        $inputCsvPath = "./tests/fixtures/csv/non-existing.csv";
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Please provide an existing input CSV");
        $actual = CsvExtractor::getCSVData($inputCsvPath, StudentModel::getFields());
    }

}
