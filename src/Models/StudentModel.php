<?php

namespace App\Models;

use App\Database;

final class StudentModel extends Model {

    protected string $tableName = "student";

    public function __construct()
    {
        parent::__construct();
        $dbName = Database::$dbName;
        $tableName = $this->tableName;
        $this->database->getConn()->query("CREATE TABLE IF NOT EXISTS $dbName.$tableName(
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            email TEXT NOT NULL,
            first_name TEXT NOT NULL,
            last_name TEXT NOT NULL,
            on_track_status TEXT CHECK(on_track_status IN ('Behind', 'On Track')) NOT NULL
        )");
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