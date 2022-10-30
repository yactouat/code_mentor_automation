<?php

namespace Udacity\Apps\Web;

/**
 * this class is responsible for storing web routing information
 */
final class Routes {

    /**
     * gets the routes of the app
     *
     * @return array - an array of the routes and their linked controllers/methods, sorted by HTTP verb
     */
    public static function getRegisteredRoutes(): array {
        return [
            'GET' => [
                '/' => ['Resource\SessionLeadsController', 'index'],
                'emails' => ['EmailsController', 'create'],
                'login' => ['Resource\SessionLeadsController', 'login'],
                'logout' => ['Resource\SessionLeadsController', 'logout'],
                'session-leads/create' => ['Resource\SessionLeadsController', 'create']
            ],
            'POST' => [
                'emails' => ['EmailsController', 'persist'],
                'login' => ['Resource\SessionLeadsController', 'login'],
                'session-leads/create' => ['Resource\SessionLeadsController', 'persist']
            ]
        ];
    }

}