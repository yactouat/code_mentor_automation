<?php

namespace Udacity;

trait AuthedTrait {

    protected function isAuthed(): bool {
        return isset($_SESSION['authed']) && $_SESSION['authed'] === true;
    }

    public static function getAuthedUserFirstName(): string {
        return !empty($_SESSION['authed_first_name']) ? $_SESSION['authed_first_name'] : '';
    }

}