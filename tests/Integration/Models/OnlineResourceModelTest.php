<?php declare(strict_types=1);

namespace Tests\Integration\Models;

use Udacity\Database;
use Udacity\Models\OnlineResourceModel;
use PHPUnit\Framework\TestCase;

final class OnlineResourceModelTest extends TestCase {

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

    public function testConstructCreatesOnlineResourceTableInDb() {
        // arrange
        $database = new Database();
        $expected = 'onlineresource';
        $onlineResource = new OnlineResourceModel("test name", "test description", "test url");
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
        $expected = "onlineresource";
        $onlineResource = new OnlineResourceModel("test name", "test description", "test url");
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
        $sessionLead = new OnlineResourceModel("test name", "test description", "test url");
        // act
        $res = $database->readQuery(
            "pragma table_info('onlineresource')"
        );
        $actual = array_map(fn($col) => $col["name"], $res);
        // assert
        $this->assertSame($expected, $actual);
    }

    public function testPersistPersistsInstanceInDb() {
        // arrange
        $expected = [
            [
                "id" => 1,
                "description" => "test description",
                "name" => "test name",
                "url" => "test URL"
            ]
        ];   
        $onlineResource = new OnlineResourceModel("test description", "test name", "test URL");
        $onlineResource->persist();
        // act
        $actual = $onlineResource->selectAll();
        // assert
        $this->assertEquals($expected, $actual);     
    }

}