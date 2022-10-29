<?php

namespace Udacity;

use Udacity\Models\SessionLeadModel;

trait AuthTrait {

    protected function isAuthed(): bool {
        switch (APP_MODE) {
            case 'web':
                return isset($_SESSION['authed']) && $_SESSION['authed'] === true;
            case 'cli':
                return $_ENV['authed'] ?? false;
            default:
                return false;
        }
    }

    public static function getAuthedUserFirstName(): string {
        switch (APP_MODE) {
            case 'web':
                return !empty($_SESSION['authed_first_name']) ? $_SESSION['authed_first_name'] : '';
            case 'cli':
                $sessionLead = new SessionLeadModel(
                    email: '',
                    first_name: '',
                    google_app_password: '',
                    user_passphrase: ''
                );
                $usr = $sessionLead->selectOneByEmail($_ENV['authed_user_email']);
                return count($usr) > 0 ? $usr['first_name'] : '';
            default:
                return false;
        }
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