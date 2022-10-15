<?php declare(strict_types=1);

namespace Tests\Unit;

use App\Database;
use PDO;
use PHPUnit\Framework\TestCase;

final class DatabaseTest extends TestCase {

    public function testConstructWithExistingDatabaseSetsDatabaseConn() {
        // arrange
        $existingDb = '/udacity_sl_automation/tests/fixtures/sql/database.db';
        fopen($existingDb, "w");
        $expected = PDO::class;
        $database = new Database($existingDb);
        // act
        $actual = $database->getConn();
        // tear down
        unlink($existingDb);
        // assert
        $this->assertInstanceOf($expected, $actual);
    }

    public function testConstructWithNonExistingDatabaseSetsDatabaseConn() {
        // arrange
        $nonExistingDb = '/udacity_sl_automation/tests/fixtures/sql/non_existing_database.db';
        $expected = PDO::class;
        $database = new Database($nonExistingDb);
        // act
        $actual = $database->getConn();
        // tear down
        unlink($nonExistingDb);
        // assert
        $this->assertInstanceOf($expected, $actual);
    }
    
    public function testConstructCreatesDatabase() {
        $dbPath = '/udacity_sl_automation/tests/fixtures/sql/database.db';
        $database = new Database($dbPath);
        $expected = 'udacity_sl_automation';
        $query = $database->getConn()->query('PRAGMA database_list');
        $filtered = array_filter($query->fetchAll(), function($db) use($expected) {
            return $db["name"] === $expected;
        });
        $actual = array_pop($filtered);
        unlink($dbPath);
        $this->assertSame($expected, $actual["name"]);
    }

}