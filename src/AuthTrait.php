<?php

namespace Udacity;

use Udacity\Models\SessionLeadModel;

/**
 * this trait is responsible for managing the auth state cross namespaces
 */
trait AuthTrait {

    /**
     * checks if the user is authenticated whether in web or cli mode
     *
     * @return boolean - if the user is authenticated
     */
    protected function isAuthed(): bool {
        switch ($_ENV['APP_MODE']) {
            case 'web':
                return isset($_SESSION['authed']) && $_SESSION['authed'] === true;
            case 'cli':
                return $_ENV['authed'] ?? false;
            default:
                return false;
        }
    }

    /**
     * gets the authenticated user first name, whether in web or cli mode
     *
     * @return string the authed user first name or an empty string
     */
    public static function getAuthedUserFirstName(): string {
        switch ($_ENV['APP_MODE']) {
            case 'web':
                return !empty($_SESSION['authed_first_name']) ? $_SESSION['authed_first_name'] : '';
            case 'cli':
                $usr = SessionLeadModel::getEmptyInstance()->selectOneByEmail($_ENV['authed_user_email']);
                return count($usr) > 0 ? $usr['first_name'] : '';
            default:
                return '';
        }
    }

    /**
     * logs a user in outside the context of a web controller
     *
     * @param string $email
     * @param string $password
     * @return boolean - the outcome of the operation
     */
    public static function logUserIn(string $email, string $password): bool {
        $usr = SessionLeadModel::getEmptyInstance()->selectOneByEmail($email);
        return count($usr) > 0 && password_verify(
            $password,
            $usr['user_passphrase']
        );
    }

}