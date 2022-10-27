<?php

namespace Udacity\Apps\Web;

final class Routes {

    public static function getRegisteredRoutes(): array {
        // TODO test login template + status code
        return [
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
    }

}