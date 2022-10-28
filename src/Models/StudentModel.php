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
        if (count(self::validateInputFields([
            'on_track_status' => $this->on_track_status
        ])) <= 0) {
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
    }

    public static function validateInputFields(array $fields): array
    {
        $errors = [];
        if (!in_array($fields['on_track_status'], ['Behind', 'On Track'])) {
            $errors[] = "🛤️ On track status can only be 'Behind' or 'On Track'";
        }
        return $errors;
    }

}