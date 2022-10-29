<?php

namespace Udacity;

use Udacity\Models\SessionLeadModel;

trait AuthTrait {

    protected function isAuthed(string $mode = 'web'): bool {
        switch ($mode) {
            case 'web':
                return isset($_SESSION['authed']) && $_SESSION['authed'] === true;
        }
    }

    public static function getAuthedUserFirstName(): string {
        return !empty($_SESSION['authed_first_name']) ? $_SESSION['authed_first_name'] : '';
    }

    public static function logUserIn(string $email, string $password): bool {
        $sessionLead = new SessionLeadModel(
            email: '',
            first_name: '',
            google_app_password: '',
            user_passphrase: ''
        );
        $usr = $sessionLead->selectOneByEmail($email);
        return count($usr) > 0 && password_verify(
            $password,
            $usr['user_passphrase']
        );
    }

}