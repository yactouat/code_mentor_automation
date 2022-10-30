<?php declare(strict_types=1);

namespace Tests\Integration\Apps\Web;

use PHPUnit\Framework\TestCase;
use Tests\Integration\EnvLoaderTrait;
use Udacity\Apps\Web\WebApp;
use Udacity\Apps\Web\Controllers\NotFoundController;
use Udacity\Apps\Web\Controllers\Resource\SessionLeadsController;

final class WebAppTest extends TestCase {

    use AuthenticateTrait;
    use EnvLoaderTrait;

    protected function setUp(): void
    {
        $this->loadEnv();
    }

    public function testGetRequestRouteWithHomeRouteGetsCorrectRoute() {
        $expected = '/';
        $actual = WebApp::parseRequestRoute('/');
        $this->assertEquals($expected, $actual);
    }

    public function testGetRequestRouteWithGivenRouteGetsCorrectRoute() {
        $expected = 'test';
        $actual = WebApp::parseRequestRoute('/test');
        $this->assertEquals($expected, $actual);
    }

    public function testGetRequestRouteWithGivenNestedRouteGetsCorrectRoute() {
        $expected = 'test/sub';
        $actual = WebApp::parseRequestRoute('/test/sub');
        $this->assertEquals($expected, $actual);
    }

    public function testGetRequestRouteWithGivenRouteWithIdentifierGetsCorrectRoute() {
        $expected = 'test/1';
        $actual = WebApp::parseRequestRoute('/test/1');
        $this->assertEquals($expected, $actual);
    }

    public function testGetRequestRouteWithTrailingSlashRouteRemovesTrailingSlash() {
        $expected = 'test';
        $actual = WebApp::parseRequestRoute('/test/');
        $this->assertEquals($expected, $actual);
    }

    public function testGetRequestRouteWithQueryStringsRemovesQueryStrings() {
        $expected = 'test';
        $actual = WebApp::parseRequestRoute('/test?q=v');
        $this->assertEquals($expected, $actual);
    }

    public function testGetRequestRouteWithSlashQueryStringsRemovesQueryStringsAndSlash() {
        $expected = 'test';
        $actual = WebApp::parseRequestRoute('/test/?q=v');
        $this->assertEquals($expected, $actual);
    }

    public function testGetRequestRouteWithMultipleQueryStringsRemovesAllQueryStrings() {
        $expected = 'test';
        $actual = WebApp::parseRequestRoute('/test?q=v&q2=v2');
        $this->assertEquals($expected, $actual);
    }

    public function testGetRequestRouteWithSlashAndMultipleQueryStringsRemovesAllQueryStringsAndSlash() {
        $expected = 'test';
        $actual = WebApp::parseRequestRoute('/test/?q=v&q2=v2');
        $this->assertEquals($expected, $actual);
    }

    public function testHandleRequestWithUnknownRouteSets404StatusCode() {
        $expected = 404;
        $app = new WebApp('/var/www/tests/fixtures');
        $app->handleRequest('/unknown');
        $actual = $app->getStatusCode();
        $this->assertEquals($expected, $actual);
    }

    public function testHandleRequestWithHomeRouteSets200StatusCode() {
        $this->authenticate();
        $expected = 200;
        $app = new WebApp('/var/www/tests/fixtures');
        $app->handleRequest('/');
        $actual = $app->getStatusCode();
        $this->assertEquals($expected, $actual);
    }

    public function testGetControllerWithHomeRouteGetsSessionLeadsController() {
        $expected = SessionLeadsController::class;
        $app = new WebApp('/var/www/tests/fixtures');
        $app->handleRequest('/');
        $actual = $app->getController();
        $this->assertInstanceOf($expected, $actual);
    }

    public function testGetControllerWithUnknownRouteGetsNotFoundController() {
        $expected = NotFoundController::class;
        $app = new WebApp('/var/www/tests/fixtures');
        $app->handleRequest("/unknown");
        $actual = $app->getController();
        $this->assertInstanceOf($expected, $actual);
    }

    public function testGetResponseOutputWithUnkownRouteGets404Page() {
        $expected = str_replace([' ', "\n"], ['', ''], file_get_contents('/var/www/tests/fixtures/views/not-found.html'));
        $app = new WebApp('/var/www/tests/fixtures');
        $app->handleRequest("/unknown");
        $actual = $app->getResponseOutput();
        $this->assertEquals($expected, str_replace([' ', "\n"], ['', ''], $actual));
    }

    public function testGetResponseOutputWithHomeRouteGetsHomePage() {
        $this->authenticate();
        $expected = str_replace([' ', "\n"], ['', ''], file_get_contents('/var/www/tests/fixtures/views/home.html'));
        $app = new WebApp('/var/www/tests/fixtures');
        $app->handleRequest('/');
        $actual = $app->getResponseOutput();
        $this->assertEquals($expected, str_replace([' ', "\n"], ['', ''], $actual));
    }

    public function testGetResponseOutputWithSessionLeadsCreateRouteGetsSignupPage() {
        $expected = str_replace(' ', '', file_get_contents('/var/www/tests/fixtures/views/session-leads.create.html'));
        $app = new WebApp('/var/www/tests/fixtures');
        $app->handleRequest("/session-leads/create");
        $actual = $app->getResponseOutput();
        $this->assertEquals($expected, str_replace(' ', '', $actual));
    }

    public function testHandleRequestWithHomeRouteUnauthedSets401StatusCode() {
        $expected = 401;
        $app = new WebApp('/var/www/tests/fixtures');
        $app->handleRequest('/');
        $actual = $app->getStatusCode();
        $this->assertEquals($expected, $actual);
    }

    public function testGetResponseOutputWithSessionLeadsLoginRouteGetsLoginPage() {
        $expected = str_replace(' ', '', file_get_contents('/var/www/tests/fixtures/views/session-leads.login.html'));
        $app = new WebApp('/var/www/tests/fixtures');
        $app->handleRequest('/login');
        $actual = $app->getResponseOutput();
        $this->assertEquals($expected, str_replace(' ', '', $actual));
    }

    public function testHandleRequestWithLoginRouteUnauthedSets200() {
        $expected = 200;
        $app = new WebApp('/var/www/tests/fixtures');
        $app->handleRequest('/login');
        $actual = $app->getStatusCode();
        $this->assertEquals($expected, $actual);
    }

    public function testGetResponseOutputWithSessionLeadsLoginRouteAuthedGetsHomePage() {
        $this->authenticate();
        $expected = str_replace([' ', "\n"], ['', ''], file_get_contents('/var/www/tests/fixtures/views/home.html'));
        $app = new WebApp('/var/www/tests/fixtures');
        $app->handleRequest('/login');
        $actual = $app->getResponseOutput();
        $this->assertEquals($expected, str_replace([' ', "\n"], ['', ''], $actual));
    } 

    public function testGetResponseOutputWithSessionLeadsLogoutRouteGetsLoginPage() {
        $expected = str_replace([' ', "\n"], ['', ''], file_get_contents('/var/www/tests/fixtures/views/session-leads.login.html'));
        $app = new WebApp('/var/www/tests/fixtures');
        $app->handleRequest('/logout');
        $actual = $app->getResponseOutput();
        $this->assertEquals($expected, str_replace([' ', "\n"], ['', ''], $actual));
    }

    public function testHandleRequestWithLogoutRouteSets200StatusCode() {
        $expected = 200;
        $app = new WebApp('/var/www/tests/fixtures');
        $app->handleRequest('/logout');
        $actual = $app->getStatusCode();
        $this->assertEquals($expected, $actual);
    }

    public function testGetResponseOutputWithEmailBehindStudentsRouteAuthedGetsRelevantForm() {
        $this->authenticate();
        $expected = str_replace([' ', "\n", "\t"], ['', '', ''], file_get_contents('/var/www/tests/fixtures/views/emails.behind-students.create.html'));
        $_GET['type'] = "behind-students";
        $app = new WebApp('/var/www/tests/fixtures');
        $_GET['type'] = 'behind-students';
        $app->handleRequest('/emails?type=behind-students');
        $actual = $app->getResponseOutput();
        $this->assertEquals($expected, str_replace([' ', "\n", "\t"], ['', '', ''], $actual));
    }

    public function testGetResponseOutputWithEmailBehindStudentsRouteUnauthedShowsLoginPage() {
        $expected = str_replace([' ', "\n"], ['', ''], file_get_contents('/var/www/tests/fixtures/views/session-leads.login.html'));
        $app = new WebApp('/var/www/tests/fixtures');
        $_GET['type'] = 'behind-students';
        $app->handleRequest('/emails?type=behind-students');
        $actual = $app->getResponseOutput();
        $this->assertEquals($expected, str_replace([' ', "\n"], ['', ''], $actual));
    } 

    public function testOutputWithEmailsRouteAuthedButNonExistingEmailTypeGetsNotFoundPage() {
        $this->authenticate();
        $expected = str_replace([' ', "\n"], ['', ''], file_get_contents('/var/www/tests/fixtures/views/not-found.html'));
        $app = new WebApp('/var/www/tests/fixtures');
        $_GET['type'] = 'non-existing';
        $app->handleRequest('/emails?type=non-existing');
        $actual = $app->getResponseOutput();
        $this->assertEquals($expected, str_replace([' ', "\n"], ['', ''], $actual));
    }

    public function testOutputWithEmailsRouteAuthedButNonExistingEmailTypeGets404Code() {
        $this->authenticate();
        $expected = 404;
        $app = new WebApp('/var/www/tests/fixtures');
        $_GET['type'] = 'non-existing';
        $app->handleRequest('/emails?type=non-existing');
        $actual = $app->getStatusCode();
        $this->assertEquals($expected, $actual);
    }

    public function testHandleRequestWithLogoutRouteAfterHavingAuthenticatedSets200StatusCode() {
        $this->authenticate();
        $expected = 200;
        $app = new WebApp('/var/www/tests/fixtures');
        $app->handleRequest('/logout');
        $actual = $app->getStatusCode();
        $this->assertEquals($expected, $actual);
    }

    public function testGetResponseOutputWithSessionLeadsLogoutRouteAfterHavingAuthenticatedGetsLoginPage() {
        $this->authenticate();
        $expected = str_replace([' ', "\n"], ['', ''], file_get_contents('/var/www/tests/fixtures/views/session-leads.login.html'));
        $app = new WebApp('/var/www/tests/fixtures');
        $app->handleRequest('/logout');
        $actual = $app->getResponseOutput();
        $this->assertEquals($expected, str_replace([' ', "\n"], ['', ''], $actual));
    }

    public function testOutputWithEmailsRouteUnauthedGets401Code() {
        $expected = 401;
        $app = new WebApp('/var/www/tests/fixtures');
        $_GET['type'] = 'behind-students';
        $app->handleRequest('/emails?type=behind-students');
        $actual = $app->getStatusCode();
        $this->assertEquals($expected, $actual);
    }

}

