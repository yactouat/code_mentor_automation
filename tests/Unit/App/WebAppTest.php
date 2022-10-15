<?php declare(strict_types=1);

namespace Tests\Unit\App;

use PHPUnit\Framework\TestCase;
use Udacity\App\WebApp;

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

}

