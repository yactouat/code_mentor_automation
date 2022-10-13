<?php declare(strict_types=1);

namespace Tests\Unit;

use App\Database;
use PDO;
use PHPUnit\Framework\TestCase;

final class DatabaseTest extends TestCase {

    public function testConstructWithExistingDatabaseSetsDatabaseConn() {
        // arrange
        $expected = PDO::class;
        $database = new Database();
        // act
        $actual = $database->getDatabaseConn();
        // assert
        $this->assertInstanceOf($expected, $actual);
    }

}