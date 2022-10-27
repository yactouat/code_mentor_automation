<?php

namespace Udacity\Apps\Web\Controllers;

trait AuthedTrait {

    protected function isAuthed(): bool {
        return isset($_SESSION['authed']) && $_SESSION['authed'] === true;
    }

}