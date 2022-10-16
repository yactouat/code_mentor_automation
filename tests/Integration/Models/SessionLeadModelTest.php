<?php declare(strict_types=1);

namespace Tests\Integration\Models;

use Udacity\Database;
use Udacity\Models\SessionLeadModel;
use PHPUnit\Framework\TestCase;

final class SessionLeadModelTest extends TestCase {

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

    public function testConstructCreatesSessionLeadsTableInDb() {
        // arrange
        $database = new Database();
        $expected = 'sessionlead';
        $sessionLead = new SessionLeadModel("test email", "test password", "test first name");
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
        $sessionLead = new SessionLeadModel("test email", "test password", "test first name");
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
        $sessionLead = new SessionLeadModel("test email", "test password", "test first name");
        // act
        $res = $database->readQuery(
            "pragma table_info('sessionlead')"
        );
        $actual = array_map(fn($col) => $col["name"], $res);
        // assert
        $this->assertSame($expected, $actual);
    }

    public function testCreatePersistsInstanceInDb() {
        // arrange
        $expected = [
            [
                "id" => 1,
                "email" => "test email",
                "email_password" => "test password",
                "first_name" => "test first name"
            ]
        ];   
        $sessionLead = new SessionLeadModel("test email", "test password", "test first name");
        $sessionLead->persist();
        // act
        $actual = $sessionLead->selectAll();
        // assert
        $this->assertEquals($expected, $actual);     
    }

}