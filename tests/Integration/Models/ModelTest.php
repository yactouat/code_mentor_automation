<?php declare(strict_types=1);

namespace Tests\Integration\Models;

require_once "/var/www/tests/fixtures/classes/ModelWithNoTable.php";
require_once "/var/www/tests/fixtures/classes/DummyModel.php";

use Udacity\Database;
use DummyModel;
use ModelWithNoTable;
use PHPUnit\Framework\TestCase;

final class ModelTest extends TestCase {

    protected string $dbPath;

    protected function setUp(): void
    {
        $_ENV["isTesting"] = true;
        $this->dbPath = '/var/www/tests/fixtures/sql/database.db';
    }

    protected function tearDown(): void
    {
        if (isset($this->dbPath) && file_exists($this->dbPath)) {
            unlink($this->dbPath);
        }
    }

    public function testConstructWithNoTableNameSetThrows() {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("No table name set for this model");
        $model = new ModelWithNoTable();
    }

    public function testSelectAllWithRecordsInDbGetsAllRecords() {
        // arrange
        $expected = [
            [
                "id" => 1,
                "some_field" => "test"
            ],
            [
                "id" => 2,
                "some_field" => "test2"
            ],
            [
                "id" => 3,
                "some_field" => "test3"
            ],
            [
                "id" => 4,
                "some_field" => "test4"
            ]
        ];
        $database = new Database();
        $dummy = new DummyModel();
        $dbName = Database::$dbName;
        $database->writeQuery("INSERT INTO $dbName.dummy (some_field) VALUES 
            ('test'),
            ('test2'),
            ('test3'),
            ('test4')
        ");
        // act
        $actual = $dummy->selectAll();
        // assert
        $this->assertEquals($expected, $actual);
    }

}
