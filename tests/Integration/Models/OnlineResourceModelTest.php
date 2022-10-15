<?php declare(strict_types=1);

namespace Tests\Integration\Models;

use App\Database;
use App\Models\OnlineResourceModel;
use PHPUnit\Framework\TestCase;

final class OnlineResourceModelTest extends TestCase {

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

    public function testConstructCreatesOnlineResourceTableInDb() {
        // arrange
        $database = new Database();
        $expected = 'onlineresource';
        $onlineResource = new OnlineResourceModel($database);
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
        $expected = "onlineresource";
        $onlineResource = new OnlineResourceModel();
        $actual = $onlineResource->getTableName();
        $this->assertSame($expected, $actual);
    }

    public function testConstructSetsCorrectDbFields() {
        // arrange
        $database = new Database();
        $expected = [
            "id",
            "description",
            "name",
            "url"
        ];
        $sessionLead = new OnlineResourceModel($database);
        // act
        $query = $database->getConn()->query(
            "pragma table_info('onlineresource')"
        );
        $actual = array_map(fn($col) => $col["name"], $query->fetchAll());
        // assert
        $this->assertSame($expected, $actual);
    }

}