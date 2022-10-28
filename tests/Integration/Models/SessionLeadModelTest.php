<?php declare(strict_types=1);

namespace Tests\Integration\Models;

use Udacity\Models\SessionLeadModel;
use PHPUnit\Framework\TestCase;
use Tests\EnvLoaderTrait;

final class SessionLeadModelTest extends TestCase {

    use EnvLoaderTrait;

    protected function setUp(): void
    {
        $this->loadEnv();
        $this->database->writeQuery('TRUNCATE udacity_sl_automation.sessionlead');
    }

    protected function verifyUser(array $expected, array $actual) {
        $this->assertEquals($expected['email'], $actual['email']);
        $this->assertEquals($expected['first_name'], $actual['first_name']);
        $this->assertTrue(password_verify($expected['google_app_password'], $actual['google_app_password']));
        $this->assertTrue(password_verify($expected['user_passphrase'], $actual['user_passphrase']));
    }

    public function testPersistPersistsInstanceInDb() {
        // arrange
        $expected = [
            "id" => 1,
            "email" => "test email",
            "first_name" => "test first name",
            "google_app_password" => "test google app password",
            "user_passphrase" => "test user password"
        ];   
        $sessionLead = new SessionLeadModel(
            email: "test email", 
            first_name: "test first name", 
            google_app_password: "test google app password",
            user_passphrase: "test user password"
        );
        $sessionLead->persist();
        // act
        $actual = $sessionLead->selectAll()[0];
        // assert
        $this->verifyUser($expected, $actual);    
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
        $expected = htmlspecialchars('<script>alert("I am a bad script")</script>', ENT_QUOTES);
        $sessionLead = new SessionLeadModel(
            email: 'test email', 
            first_name: 'test first name', 
            google_app_password: '<script>alert("I am a bad script")</script>',
            user_passphrase: 'test user password'
        );
        $sessionLead->persist();
        // act
        $actual = $sessionLead->selectAll()[0];
        // assert
        $this->assertTrue(password_verify($expected, $actual['google_app_password']));  
    }

    public function testPersistWithFirstNameContainingCodePersistsSanitizedStringInDb() {
        // arrange
        $expected = htmlspecialchars('<script>alert("I am a bad script")</script>', ENT_QUOTES);
        $sessionLead = new SessionLeadModel(
            email: 'test email', 
            first_name: '<script>alert("I am a bad script")</script>', 
            google_app_password: 'some password',
            user_passphrase: 'test user password'
        );
        $sessionLead->persist();
        // act
        $actual = $sessionLead->selectAll();
        // assert
        $this->assertEquals($expected, $actual[0]['first_name']);  
    }

    public function testPersistWithUserPasswordContainingCodePersistsSanitizedStringInDb() {
        // arrange
        $expected = htmlspecialchars('<script>alert("I am a bad script")</script>', ENT_QUOTES);
        $sessionLead = new SessionLeadModel(
            email: 'test email', 
            first_name: 'some first name', 
            google_app_password: 'some password',
            user_passphrase: '<script>alert("I am a bad script")</script>'
        );
        $sessionLead->persist();
        // act
        $actual = $sessionLead->selectAll()[0];
        // assert
        $this->assertTrue(password_verify($expected, $actual['user_passphrase']));     
    }

    public function testValidateInputFieldsWithNoPasswordPushesCorrectErrorInErrorsArrray() {
        $expected = '🔑 Your user passphrase is missing';
        $actual = SessionLeadModel::validateInputFields([]);
        $this->assertTrue(in_array($expected, $actual));
    }

    public function testValidateInputFieldsWithAnEmailThatTooLongPushesCorrectErrorInErrorsArrray() {
        $expected = '📧 Malformed email address';
        $actual = SessionLeadModel::validateInputFields([
            "email" => "testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest@gmail.com"
        ]);
        $this->assertTrue(in_array($expected, $actual));
    }

    public function testSelectOneByEmailReturnsRelevantUserData() {
        // arrange
        $expected = [
            "id" => 1,
            "email" => "test@gmail.com",
            "first_name" => "test first name",
            "google_app_password" => "test google app password",
            "user_passphrase" => "test user password",
        ];   
        $sessionLead = new SessionLeadModel(
            email: "test@gmail.com", 
            first_name: "test first name", 
            google_app_password: "test google app password",
            user_passphrase: "test user password"
        );
        $sessionLead->persist();
        // act
        $actual = $sessionLead->selectOneByEmail("test@gmail.com");
        // assert
        $this->verifyUser($expected, $actual);
    }

    public function testSelectOneByEmailWithInvalidEmailReturnsEmptyArray() {
        // arrange
        $expected = [];   
        $sessionLead = new SessionLeadModel(
            email: 'test@gmail.com', 
            first_name: 'test first name', 
            google_app_password: 'test google app password',
            user_passphrase: 'test user password'
        );
        $sessionLead->persist();
        // act
        $actual = $sessionLead->selectOneByEmail("test@.com");
        // assert
        $this->assertEquals($expected, $actual);
    }

    public function testSelectOneByEmailWithNonExistingEmailReturnsEmptyArray() {
        // arrange
        $expected = [];   
        $sessionLead = new SessionLeadModel(
            email: "test@gmail.com", 
            first_name: "test first name", 
            google_app_password: "test google app password",
            user_passphrase: "test user password"
        );
        $sessionLead->persist();
        // act
        $actual = $sessionLead->selectOneByEmail("test2@gmail.com");
        // assert
        $this->assertEquals($expected, $actual);
    }

    public function testValidateInputFieldsWithAlreadyExistingEmailPushesCorrectErrorInErrorsArrray() {
        $expected = '📧 This email already exists in our system';
        $sessionLead = new SessionLeadModel(
            email: 'yactouat@hotmail.com', 
            first_name: 'test first name', 
            google_app_password: 'test google app password',
            user_passphrase: 'test user password'
        );
        $sessionLead->persist();
        $actual = SessionLeadModel::validateInputFields([
            'email' => 'yactouat@hotmail.com',
            'first_name' => 'test first name',
            'google_app_password' => 'test google app password',
            'user_passphrase' => 'test user password'
        ]);
        $this->assertTrue(in_array($expected, $actual));
    }

}