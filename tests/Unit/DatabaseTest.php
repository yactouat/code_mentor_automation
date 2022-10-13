<?php declare(strict_types=1);

namespace Tests\Unit;

use App\Database;
use PDO;
use PHPUnit\Framework\TestCase;

final class DatabaseTest extends TestCase {

    public function testConstructWithExistingDatabaseSetsDatabaseConn() {
        // arrange
        $expected = PDO::class;
        $database = new Database("./tests/fixtures/sql/database.db");
        // act
        $actual = $database->getDatabaseConn();
        // assert
        $this->assertInstanceOf($expected, $actual);
    }

    public function testConstructWithNonExistingDatabaseSetsDatabaseConn() {
        // arrange
        $nonExistingDb = './tests/fixtures/sql/non_existing_database.db';
        $expected = PDO::class;
        $database = new Database($nonExistingDb);
        // act
        $actual = $database->getDatabaseConn();
        // assert
        $this->assertInstanceOf($expected, $actual);
        // tear down
        unlink($nonExistingDb);
    }


}