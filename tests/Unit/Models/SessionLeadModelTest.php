<?php declare(strict_types=1);

namespace Tests\Unit\Models;

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

    public function testConstructCreatesSessionLeadsTableInDB() {
        // arrange
        $database = new Database();
        $expected = 'sessionlead';
        $sessionLead = new SessionLeadModel($database);
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

    public function testConstructSetsCorrectTableName() {
        $expected = "sessionlead";
        $sessionLead = new SessionLeadModel();
        $actual = $sessionLead->getTableName();
        $this->assertSame($expected, $actual);
    }

}

// TODO test models tables fields