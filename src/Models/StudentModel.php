<?php

namespace Udacity\Models;

use Udacity\Database;

final class StudentModel extends Model {

    protected string $tableName = "student";

    public function __construct(
        private string $email, 
        private string $first_name,
        private string $last_name,
        private string $on_track_status
    )
    {
        parent::__construct();
        $dbName = Database::$dbName;
        $tableName = $this->tableName;
        $this->database->writeQuery("CREATE TABLE IF NOT EXISTS $dbName.$tableName(
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE,
            email TEXT NOT NULL UNIQUE,
            first_name TEXT NOT NULL,
            last_name TEXT NOT NULL,
            on_track_status TEXT CHECK(on_track_status IN ('Behind', 'On Track')) NOT NULL
        )");
    }

    public static function getCsvFields(): array {
        return [
            "On-Track Status",
            "First Name",
            "Last Name",
            "Email"
        ];
    }

    public function persist(): void {
        $email = $this->email;
        $first_name = $this->first_name;
        $last_name = $this->last_name;
        $on_track_status = $this->on_track_status;
        $dbName = Database::$dbName;
        $tableName = $this->tableName;
        $query = "INSERT INTO $dbName.$tableName (email, first_name, last_name, on_track_status) VALUES 
            ('$email', '$first_name', '$last_name', '$on_track_status')";
        $this->database->writeQuery($query);
    }

    public static function validateInputFields(array $fields): array
    {
        return [];
    }

}