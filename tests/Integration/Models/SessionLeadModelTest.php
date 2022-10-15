<?php declare(strict_types=1);

namespace Tests\Integration\Models;

use App\Database;
use App\Models\SessionLeadModel;
use PHPUnit\Framework\TestCase;

final class SessionLeadModelTest extends TestCase {

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

    public function testConstructCreatesSessionLeadsTableInDb() {
        // arrange
        $database = new Database();
        $expected = 'sessionlead';
        $sessionLead = new SessionLeadModel($database);
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
        $expected = "sessionlead";
        $sessionLead = new SessionLeadModel();
        $actual = $sessionLead->getTableName();
        $this->assertSame($expected, $actual);
    }

    public function testConstructSetsCorrectDbFields() {
        // arrange
        $database = new Database();
        $expected = [
            "id",
            "email",
            "email_password",
            "first_name"
        ];
        $sessionLead = new SessionLeadModel($database);
        // act
        $res = $database->readQuery(
            "pragma table_info('sessionlead')"
        );
        $actual = array_map(fn($col) => $col["name"], $res);
        // assert
        $this->assertSame($expected, $actual);
    }

}