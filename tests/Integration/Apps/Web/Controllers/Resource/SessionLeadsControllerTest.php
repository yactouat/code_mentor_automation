<?php declare(strict_types=1);

namespace Tests\Integration\Apps\Web\Controllers\Resource;

use PHPUnit\Framework\TestCase;
use Tests\Integration\Apps\Web\AuthenticateTrait;
use Tests\Integration\EnvLoaderTrait;
use Udacity\Apps\Web\Controllers\Resource\SessionLeadsController;

final class SessionLeadsControllerTest extends TestCase {

    use AuthenticateTrait;
    use EnvLoaderTrait;

    protected function setUp(): void
    {
        $this->loadEnv();
    }

    public function testPersistWithNoSubmitFieldReturnsRelevantAlert() {
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

    public function testPersistWithNoEmailFieldReturnsRelevantAlert() {
        $ctlr = new SessionLeadsController();
        $expected = str_replace(' ', '', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div>📧 Your email address is missing</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>');
        $actual = str_replace(' ', '', $ctlr->persist());
        $this->assertTrue(str_contains($actual, $expected));
    }

    public function testPersistWithNoGoogleAppPasswordFieldReturnsRelevantAlert() {
        $ctlr = new SessionLeadsController();
        $expected = str_replace(' ', '', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div>🔑 Your Google application password is missing</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>');
        $actual = str_replace(' ', '', $ctlr->persist());
        $this->assertTrue(str_contains($actual, $expected));
    }

    public function testPersistWithNoFirstNameFieldReturnsRelevantAlert() {
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

    public function testLoginWithValidInputSetsSession() {
        $this->authenticate();
        $this->assertTrue($_SESSION['authed']);
        $this->assertEquals('Yacine', $_SESSION['authed_first_name']);
    }

    public function testLoginWithInvalidInputDoesNotSetSession() {
        $ctlr = new SessionLeadsController();
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [
            'submit' => '1',
            'email' => 'test@gmail.com',
            'first_name' => 'test first name',
            'google_app_password' => 'test google app password',
            'user_passphrase' => 'test user password',
        ];
        $ctlr->persist();
        $_SESSION = []; // resetting the session since `persist` fills it
        $_POST = [
            'submit' => '1',
            'email' => 'test@gmail.com',
            'user_passphrase' => 'wrong passphrase',
        ];
        $ctlr->login();
        $this->assertFalse($_SESSION['authed']);
    }

    public function testLoginOutputWithGoodCredsReturnsHomePage() {
        $expected = str_replace([' ', "\n"], ['', ''], file_get_contents('/var/www/tests/fixtures/views/home.html'));
        $ctlr = new SessionLeadsController();
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [
            'submit' => '1',
            'email' => 'test@gmail.com',
            'first_name' => 'test first name',
            'google_app_password' => 'test google app password',
            'user_passphrase' => 'test user password',
        ];
        $ctlr->persist();
        $_SESSION = []; // resetting the session since `persist` fills it
        $_POST = [
            'submit' => '1',
            'email' => 'test@gmail.com',
            'user_passphrase' => 'test user password',
        ];
        $actual = $ctlr->login();
        $this->assertEquals($expected, str_replace([' ', "\n"], ['', ''], $actual));
    } 

    public function testLoginOutputWithBadCredsReturnsLoginPage() {
        $expected = str_replace(' ', '', file_get_contents('/var/www/tests/fixtures/views/session-leads.login.html'));
        $ctlr = new SessionLeadsController();
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [
            'submit' => '1',
            'email' => 'test@gmail.com',
            'user_passphrase' => 'test user password',
        ];
        $actual = $ctlr->login();
        $this->assertEquals($expected, str_replace(' ', '', $actual));
    } 

    public function testLoginWithGoodCredsSets200StatusCode() {
        $expected = 200;
        $ctlr = new SessionLeadsController();
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [
            'submit' => '1',
            'email' => 'test@gmail.com',
            'first_name' => 'test first name',
            'google_app_password' => 'test google app password',
            'user_passphrase' => 'test user password',
        ];
        $ctlr->persist();
        $_SESSION = []; // resetting the session since `persist` fills it
        $_POST = [
            'submit' => '1',
            'email' => 'test@gmail.com',
            'user_passphrase' => 'test user password',
        ];
        $ctlr->login();
        $this->assertEquals($expected, $ctlr->getStatusCode());
    }

    public function testLoginWithBadCredsSets401StatusCode() {
        $expected = 401;
        $ctlr = new SessionLeadsController();
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [
            'submit' => '1',
            'email' => 'test@gmail.com',
            'user_passphrase' => 'test user password',
        ];
        $ctlr->login();
        $this->assertEquals($expected, $ctlr->getStatusCode());
    }

    public function testLoginWithoutSubmitFieldsReturnsLoginPage() {
        $expected = str_replace(' ', '', file_get_contents('/var/www/tests/fixtures/views/session-leads.login.html'));
        $ctlr = new SessionLeadsController();
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [
            'submit' => '1',
            'email' => 'test@gmail.com',
            'first_name' => 'test first name',
            'google_app_password' => 'test google app password',
            'user_passphrase' => 'test user password',
        ];
        $ctlr->persist();
        $_SESSION = []; // resetting the session since `persist` fills it
        $_POST = [
            'email' => 'test@gmail.com',
            'user_passphrase' => 'test user password',
        ];
        $actual = $ctlr->login();
        $this->assertEquals($expected, str_replace(' ', '', $actual));
    } 

    public function testPersistWithValidInputSetsMsmtprcFile() {
        $expected = file_get_contents('/var/www/tests/fixtures/msmtp/msmtprc');
        $ctlr = new SessionLeadsController();
        $_POST = [
            "submit" => "1",
            "email" => "test@gmail.com",
            "first_name" => "test first name",
            "google_app_password" => "googleapppassword",
            "user_passphrase" => "test user password",
        ];
        $ctlr->persist();
        $actual = file_get_contents('/etc/msmtprc.test');
        $this->assertEquals($expected, $actual);
    }

    public function testPersistWithInvalidInputDoesNotSetMsmtprcConf() {
        $ctlr = new SessionLeadsController();
        $_POST = [
            "submit" => "1",
            "email" => "test@.com",
            "first_name" => "test first name",
            "google_app_password" => "googleapppassword",
            "user_passphrase" => "test user password",
        ];
        $ctlr->persist();
        $this->assertTrue(!file_exists('/etc/msmtprc.test'));
        $this->assertTrue(!file_exists('/etc/msmtprc'));
    }

}