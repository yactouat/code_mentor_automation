<?php declare(strict_types=1);

namespace Tests\Unit\Apps\Web;

use PHPUnit\Framework\TestCase;
use Udacity\Apps\Web\Routes;

final class RoutesTest extends TestCase {

    public function testGetRegisteredRoutesReturnsExpectedRoutes() {
        $expected = [
            'GET' => [
                '/' => ['Resource\SessionLeadsController', 'index'],
                'emails' => ['Resource\EmailsController', 'create'],
                'login' => ['Resource\SessionLeadsController', 'login'],
                'logout' => ['Resource\SessionLeadsController', 'logout'],
                'session-leads/create' => ['Resource\SessionLeadsController', 'create']
            ],
            'POST' => [
                'emails' => ['Resource\EmailsController', 'persist'],
                'login' => ['Resource\SessionLeadsController', 'login'],
                'session-leads/create' => ['Resource\SessionLeadsController', 'persist']
            ]
        ];
        $actual = Routes::getRegisteredRoutes();
        $this->assertEquals($expected, $actual);
    }

}

