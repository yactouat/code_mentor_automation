<?php declare(strict_types=1);

namespace Tests\Unit\Apps\Web;

use PHPUnit\Framework\TestCase;
use Udacity\Apps\Web\Routes;

final class RoutesTest extends TestCase {

    public function testGetRegisteredRoutesReturnsExpectedRoutes() {
        $expected = [
            "GET" => [
                '/' => ['Resource\SessionLeadsController', 'index'],
                'login' => ['Resource\SessionLeadsController', 'login'],
                'session-leads/create' => ['Resource\SessionLeadsController', 'create']
            ],
            'POST' => [
                'login' => ['Resource\SessionLeadsController', 'login'],
                'session-leads/create' => ['Resource\SessionLeadsController', 'persist']
            ]
        ];
        $actual = Routes::getRegisteredRoutes();
        $this->assertEquals($expected, $actual);
    }

}

