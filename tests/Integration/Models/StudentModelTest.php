<?php declare(strict_types=1);

namespace Tests\Integration\Models;

use App\Database;
use App\Models\StudentModel;
use PHPUnit\Framework\TestCase;

final class StudentModelTest extends TestCase {

    protected string $dbPath;

    protected function setUp(): void
    {

        $_ENV["isTesting"] = true;
        $this->dbPath = '/udacity_sl_automation/tests/fixtures/sql/database.db';
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
        $student = new StudentModel($database);
        // act
        $query = $database->getConn()->query(
            "SELECT name FROM sqlite_schema WHERE type='table' ORDER BY name"
        );
        $res = $query->fetchAll();
        $filtered = array_filter($res, function($table) use($expected) {
            return $table["name"] === $expected;
        });
        $actual = array_pop($filtered);
        // assert
        $this->assertSame($expected, $actual["name"]);
    }

    public function testConstructSetsCorrectDbTableName() {
        $expected = "student";
        $student = new StudentModel();
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
        $sessionLead = new StudentModel($database);
        // act
        $query = $database->getConn()->query(
            "pragma table_info('student')"
        );
        $actual = array_map(fn($col) => $col["name"], $query->fetchAll());
        // assert
        $this->assertSame($expected, $actual);
    }

}