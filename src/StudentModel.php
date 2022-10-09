<?php

namespace App;

final class StudentModel {

    public static function getFields(): array {
        return [
            "On-Track Status",
            "First Name",
            "Last Name",
            "Email"
        ];
    }

}