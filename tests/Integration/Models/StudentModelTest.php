<?php declare(strict_types=1);

namespace Tests\Integration\Models;

use Udacity\Database;
use Udacity\Models\StudentModel;
use PHPUnit\Framework\TestCase;

final class StudentModelTest extends TestCase {

    protected string $dbPath;

    protected function setUp(): void
    {

        $_ENV["isTesting"] = true;
        $this->dbPath = '/var/www/tests/fixtures/sql/database.db';
    }

    protected function tearDown(): void
    {
        if (isset($this->dbPath)) {
            unlink($this->dbPath);
        }
    }

    public function testConstructCreatesStudentTableInDb() {
        // arrange
        $database = new Database();
        $expected = 'student';
        $student = new StudentModel('test email', 'test first name', 'test last name', 'Behind');
        // act
        $res = $database->readQuery(
            "SELECT name FROM sqlite_schema WHERE type='table' ORDER BY name"
        );
        $filtered = array_filter($res, function($table) use($expected) {
            return $table["name"] === $expected;
        });
        $actual = array_pop($filtered);
        // assert
        $this->assertSame($expected, $actual["name"]);
    }

    public function testConstructSetsCorrectDbTableName() {
        $expected = "student";
        $student = new StudentModel('test email', 'test first name', 'test last name', 'Behind');
        $actual = $student->getTableName();
        $this->assertSame($expected, $actual);
    }

    public function testConstructSetsCorrectDbFields() {
        // arrange
        $database = new Database();
        $expected = [
            "id",
            "email",
            "first_name",
            "last_name",
            "on_track_status"
        ];
        $sessionLead = new StudentModel('test email', 'test first name', 'test last name', 'Behind');
        // act
        $res = $database->readQuery(
            "pragma table_info('student')"
        );
        $actual = array_map(fn($col) => $col["name"], $res);
        // assert
        $this->assertSame($expected, $actual);
    }

    public function testPersistPersistsInstanceInDb() {
        // arrange
        $expected = [
            [
                'id' => 1,
                'email' => 'test email',
                'first_name' => 'test first name',
                'last_name' => 'test last name',
                'on_track_status' => 'Behind'
            ]
        ];   
        $sessionLead = new StudentModel('test email', 'test first name', 'test last name', 'Behind');
        $sessionLead->persist();
        // act
        $actual = $sessionLead->selectAll();
        // assert
        $this->assertEquals($expected, $actual);     
    }

    // TODO test on_track_status can only be in 'Behind', 'On Track'

}