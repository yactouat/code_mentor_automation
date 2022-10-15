<?php

namespace App\Models;

final class StudentModel extends Model {

    protected string $tableName = "student";

    public function __construct()
    {
        parent::__construct();
    }

    public static function getFields(): array {
        return [
            "On-Track Status",
            "First Name",
            "Last Name",
            "Email"
        ];
    }

}