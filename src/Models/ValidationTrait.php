<?php

namespace Udacity\Models;

/**
 * this trait holds common validation logic across models
 */
trait ValidationTrait {

    protected static function validateEmail(string $inputEmail): bool {
        return filter_var($inputEmail, FILTER_VALIDATE_EMAIL) !== false;
    }

}