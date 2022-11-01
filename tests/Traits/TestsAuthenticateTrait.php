<?php

namespace Tests\Traits;

use Udacity\Apps\Web\Controllers\Resource\SessionLeadsController;
use Udacity\Models\SessionLeadModel;
use Udacity\Services\DatabaseService;

trait TestsAuthenticateTrait {

    protected function authenticate(string $email = 'test@gmail.com', string $firstName = 'Yacine'): void {
        DatabaseService::getService('write_db')->writeQuery('TRUNCATE udacity_sl_automation.sessionlead');
        $sessionLead = new SessionLeadModel($email, $firstName, 'test g app password', 'test user password');
        $sessionLead->persist();
        $ctlr = new SessionLeadsController();
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [
            'submit' => '1',
            'email' => $email,
            'user_passphrase' => 'test user password',
        ];
        $ctlr->login();
        $_POST = []; // resetting the $_POST array after login
        $_SERVER['REQUEST_METHOD'] = 'GET'; // resetting the server request method after login
    }

}