<?php declare(strict_types=1);

namespace Tests\Unit\App;

use PHPUnit\Framework\TestCase;
use Udacity\App\WebApp;
use Udacity\Controllers\HomeController;
use Udacity\Controllers\NotFoundController;

final class WebAppTest extends TestCase {

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

    public function testGetRegisteredRoutesReturnsExpectedRoutes() {
        $expected = [
            '/' => ['HomeController', 'index']
        ];
        $actual = WebApp::getRegisteredRoutes();
        $this->assertEquals($expected, $actual);
    }

    public function testHandleRequestWithUnknownRouteSets404StatusCode() {
        $expected = 404;
        $app = new WebApp();
        $app->handleRequest("/unknown");
        $actual = $app->getStatusCode();
        $this->assertEquals($expected, $actual);
    }

    public function testHandleRequestWithHomeRouteSets200StatusCode() {
        $expected = 200;
        $app = new WebApp();
        $app->handleRequest("/");
        $actual = $app->getStatusCode();
        $this->assertEquals($expected, $actual);
    }

    public function testGetControllerWithHomeRouteGetsHomeController() {
        $expected = HomeController::class;
        $app = new WebApp();
        $app->handleRequest("/");
        $actual = $app->getController();
        $this->assertInstanceOf($expected, $actual);
    }

    public function testGetControllerWithUnknownRouteGetsNotFoundController() {
        $expected = NotFoundController::class;
        $app = new WebApp();
        $app->handleRequest("/unknown");
        $actual = $app->getController();
        $this->assertInstanceOf($expected, $actual);
    }

    public function testGetResponseOutputWithUnkownRouteGets404Page() {
        $expected = file_get_contents('/var/www/tests/fixtures/views/not-found.html');
        $app = new WebApp();
        $app->handleRequest("/unknown");
        $actual = $app->getResponseOutput();
        $this->assertEquals($expected, $actual);
    }

    public function testGetResponseOutputWithHomeRouteGetsHomePage() {
        $expected = file_get_contents('/var/www/tests/fixtures/views/home.html');
        $app = new WebApp();
        $app->handleRequest("/");
        $actual = $app->getResponseOutput();
        $this->assertEquals($expected, $actual);
    }

}

