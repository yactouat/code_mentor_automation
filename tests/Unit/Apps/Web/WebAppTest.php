<?php declare(strict_types=1);

namespace Tests\Unit\Apps\Web;

use PHPUnit\Framework\TestCase;
use Udacity\Apps\Web\WebApp;
use Udacity\Apps\Web\Controllers\NotFoundController;
use Udacity\Apps\Web\Controllers\Resource\SessionLeadsController;

final class WebAppTest extends TestCase {

    protected function setUp(): void
    {
        $_SERVER['REQUEST_METHOD'] = "GET";
        $_SESSION = [];
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
        $_SESSION['authed'] = true;
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
        $expected = file_get_contents('/var/www/tests/fixtures/views/not-found.html');
        $app = new WebApp('/var/www/tests/fixtures');
        $app->handleRequest("/unknown");
        $actual = $app->getResponseOutput();
        $this->assertEquals($expected, $actual);
    }

    public function testGetResponseOutputWithHomeRouteGetsHomePage() {
        $_SESSION['authed'] = true;
        $expected = file_get_contents('/var/www/tests/fixtures/views/home.html');
        $app = new WebApp('/var/www/tests/fixtures');
        $app->handleRequest("/");
        $actual = $app->getResponseOutput();
        $this->assertEquals($expected, $actual);
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
        $app->handleRequest("/login");
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
        $_SESSION['authed'] = true;
        $expected = str_replace(' ', '', file_get_contents('/var/www/tests/fixtures/views/home.html'));
        $app = new WebApp('/var/www/tests/fixtures');
        $app->handleRequest("/login");
        $actual = $app->getResponseOutput();
        $this->assertEquals($expected, str_replace(' ', '', $actual));
    } 

}

