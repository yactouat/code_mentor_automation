<?php declare(strict_types=1);

namespace Tests\Unit\Apps\Web\Controllers;

use PHPUnit\Framework\TestCase;
use Tests\EnvLoaderTrait;
use Udacity\Apps\Web\Controllers\Resource\SessionLeadsController;

final class SessionLeadsControllerTest extends TestCase {

    use EnvLoaderTrait;

    protected function setUp(): void
    {
        $this->loadEnv();
        $this->database->writeQuery('TRUNCATE udacity_sl_automation.sessionlead');
        $_POST = [];
        $_SESSION = [];
    }

    public function testPersistWithNoSubmitFieldReturnsSignUpFormWithRelevantAlert() {
        $ctlr = new SessionLeadsController();
        $expected = str_replace(' ', '', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div>⚠️ Please send a valid form using the `submit` button</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>');
        $actual = str_replace(' ', '', $ctlr->persist());
        $this->assertTrue(str_contains($actual, $expected));
    }

    public function testPersistWithNoSubmitFieldSets400StatusCode() {
        $ctlr = new SessionLeadsController();
        $expected = 400;
        $ctlr->persist();
        $this->assertEquals($expected, $ctlr->getStatusCode());
    }

    public function testPersistWithErrorsKeepsOldUserInput() {
        $_POST["email"] = "john@doe.com";
        $ctlr = new SessionLeadsController();
        $expected = str_replace([' ', "\n"], ['', ''], '<input 
            type="email" 
            class="form-control" 
            id="email" 
            placeholder="Your Email Address"
            value="john@doe.com"
            name="email"
        >');
        $actual = str_replace([' ', "\n"], ['', ''], $ctlr->persist());
        // $this->assertEquals($expected, $actual);
        $this->assertTrue(str_contains($actual, $expected));
    }

    public function testPersistWithNoEmailFieldReturnsSignUpFormWithRelevantAlert() {
        $ctlr = new SessionLeadsController();
        $expected = str_replace(' ', '', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div>📧 Your email address is missing</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>');
        $actual = str_replace(' ', '', $ctlr->persist());
        $this->assertTrue(str_contains($actual, $expected));
    }

    public function testPersistWithNoGoogleAppPasswordFieldReturnsSignUpFormWithRelevantAlert() {
        $ctlr = new SessionLeadsController();
        $expected = str_replace(' ', '', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div>🔑 Your Google application password is missing</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>');
        $actual = str_replace(' ', '', $ctlr->persist());
        $this->assertTrue(str_contains($actual, $expected));
    }

    public function testPersistWithNoFirstNameFieldReturnsSignUpFormWithRelevantAlert() {
        $ctlr = new SessionLeadsController();
        $expected = str_replace(' ', '', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div>❌ Your first name is missing</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>');
        $actual = str_replace(' ', '', $ctlr->persist());
        $this->assertTrue(str_contains($actual, $expected));
    }

    public function testPersistWithValidInputActuallyPersistsASessionLead() {
        $ctlr = new SessionLeadsController();
        $expected = [
            'id' => 1,
            'email' => 'test@gmail.com',
            'first_name' => 'test first name',
            'google_app_password' => 'test google app password',
            'user_passphrase' => 'test user password',
        ];
        $_POST = [
            'submit' => '1',
            'email' => 'test@gmail.com',
            'first_name' => 'test first name',
            'google_app_password' => 'test google app password',
            'user_passphrase' => 'test user password',
        ];
        $ctlr->persist();
        $actual = $this->database->readQuery('SELECT * FROM sessionlead')[0];
        $this->assertEquals($expected['email'], $actual['email']);
        $this->assertEquals($expected['first_name'], $actual['first_name']);
        $this->assertTrue(password_verify($expected['google_app_password'], $actual['google_app_password']));
        $this->assertTrue(password_verify($expected['user_passphrase'], $actual['user_passphrase']));
    }

    public function testPersistWithValidInputSets201StatusCode() {
        $expected = 201;
        $ctlr = new SessionLeadsController();
        $_POST = [
            "submit" => "1",
            "email" => "test@gmail.com",
            "first_name" => "test first name",
            "google_app_password" => "test google app password",
            "user_passphrase" => "test user password",
        ];
        $ctlr->persist();
        $actual = $ctlr->getStatusCode();
        $this->assertEquals($expected, $actual);
    }

    public function testLogoutEmptiesSession() {
        $_SESSION['authed'] = true;
        (new SessionLeadsController())->logout();
        $this->assertEmpty($_SESSION);
    }

}