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
        if (isset($this->dbPath) && file_exists($this->dbPath)) {
            unlink($this->dbPath);
        }
    }

    public function testConstructCreatesSessionLeadsTableInDb() {
        // arrange
        $database = new Database();
        $expected = 'sessionlead';
        new SessionLeadModel(
            email: "test email", 
            first_name: "test first name", 
            google_app_password: "test google app password",
            user_password: "test user password"
        );
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
        $sessionLead = new SessionLeadModel(
            email: "test email", 
            first_name: "test first name", 
            google_app_password: "test google app password",
            user_password: "test user password"
        );
        $actual = $sessionLead->getTableName();
        $this->assertSame($expected, $actual);
    }

    public function testConstructSetsCorrectDbFields() {
        // arrange
        $database = new Database();
        $expected = [
            "id",
            "email",
            "first_name",
            "google_app_password",
            "user_password"
        ];
        new SessionLeadModel(
            email: "test email", 
            first_name: "test first name", 
            google_app_password: "test google app password",
            user_password: "test user password"
        );
        // act
        $res = $database->readQuery(
            "pragma table_info('sessionlead')"
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
                "email" => "test email",
                "first_name" => "test first name",
                "google_app_password" => "test google app password",
                "user_password" => "test user password",
            ]
        ];   
        $sessionLead = new SessionLeadModel(
            email: "test email", 
            first_name: "test first name", 
            google_app_password: "test google app password",
            user_password: "test user password"
        );
        $sessionLead->persist();
        // act
        $actual = $sessionLead->selectAll();
        // assert
        $this->assertEquals($expected, $actual);     
    }

    public function testValidateInputFieldsWithMalformedEmailPushesCorrectErrorInErrorsArrray() {
        $expected = '📧 Malformed email address';
        $actual = SessionLeadModel::validateInputFields(['email' => 'yactouat@.com']);
        $this->assertTrue(in_array($expected, $actual));
    }

    public function testValidateInputFieldsWithValidEmailPushesNoRelatedErrorInErrorsArrray() {
        $expected = '📧 Malformed email address';
        $actual = SessionLeadModel::validateInputFields(['email' => 'yactouat@gmail.com']);
        $this->assertTrue(!in_array($expected, $actual));
    }

    public function testPersistWithGoogleAppPasswordContainingCodePersistsSanitizedStringInDb() {
        // arrange
        $expected = htmlspecialchars('<script>alert("I am a bad script")</script>');
        $sessionLead = new SessionLeadModel(
            email: 'test email', 
            first_name: 'test first name', 
            google_app_password: '<script>alert("I am a bad script")</script>',
            user_password: 'test user password'
        );
        $sessionLead->persist();
        // act
        $actual = $sessionLead->selectAll();
        // assert
        $this->assertEquals($expected, $actual[0]['google_app_password']);     
    }

    public function testPersistWithFirstNameContainingCodePersistsSanitizedStringInDb() {
        // arrange
        $expected = htmlspecialchars('<script>alert("I am a bad script")</script>');
        $sessionLead = new SessionLeadModel(
            email: 'test email', 
            first_name: '<script>alert("I am a bad script")</script>', 
            google_app_password: 'some password',
            user_password: 'test user password'
        );
        $sessionLead->persist();
        // act
        $actual = $sessionLead->selectAll();
        // assert
        $this->assertEquals($expected, $actual[0]['first_name']);     
    }

    public function testPersistWithUserPasswordContainingCodePersistsSanitizedStringInDb() {
        // arrange
        $expected = htmlspecialchars('<script>alert("I am a bad script")</script>');
        $sessionLead = new SessionLeadModel(
            email: 'test email', 
            first_name: 'some first name', 
            google_app_password: 'some password',
            user_password: '<script>alert("I am a bad script")</script>'
        );
        $sessionLead->persist();
        // act
        $actual = $sessionLead->selectAll();
        // assert
        $this->assertEquals($expected, $actual[0]['user_password']);     
    }

    // TODO test user password presence
    // TODO test user password strength
}