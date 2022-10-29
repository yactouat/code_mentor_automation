<?php

namespace Tests\Integration\Apps\Web;

use Udacity\Apps\Web\Controllers\Resource\SessionLeadsController;
use Udacity\Database;

trait AuthenticateTrait {

    protected Database $database;

    protected function authenticate(string $email = 'test@gmail.com', string $firstName = 'Yacine'): void {
        $ctlr = new SessionLeadsController();
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [
            'submit' => '1',
            'email' => $email,
            'first_name' => $firstName,
            'google_app_password' => 'test google app password',
            'user_passphrase' => 'test user password',
        ];
        $ctlr->persist();
        $_SESSION = []; // resetting the session since `persist` fills it
        $_POST = [
            'submit' => '1',
            'email' => $email,
            'user_passphrase' => 'test user password',
        ];
        $ctlr->login();
    }

}