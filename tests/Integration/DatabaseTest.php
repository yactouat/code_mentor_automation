<?php declare(strict_types=1);

namespace Tests\Integration;

use App\Database;
use PDO;
use PHPUnit\Framework\TestCase;

final class DatabaseTest extends TestCase {

    protected function setUp(): void
    {
        $_ENV["isTesting"] = true;
    }

    public function testConstructCreatesDatabase() {
        $dbPath = '/udacity_sl_automation/tests/fixtures/sql/database.db';
        $database = new Database($dbPath);
        $expected = 'udacity_sl_automation';
        $result = $database->readQuery('PRAGMA database_list');
        $filtered = array_filter($result, function($db) use($expected) {
            return $db["name"] === $expected;
        });
        $actual = array_pop($filtered);
        unlink($dbPath);
        $this->assertSame($expected, $actual["name"]);
    }

}