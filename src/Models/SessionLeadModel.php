<?php

namespace App\Models;

use App\Database;

final class SessionLeadModel extends Model {

    protected string $tableName = "sessionlead";

    public function __construct()
    {
        parent::__construct();
        $dbName = Database::$dbName;
        $tableName = $this->tableName;
        $this->database->writeQuery("CREATE TABLE IF NOT EXISTS $dbName.$tableName(
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE,
            email TEXT NOT NULL UNIQUE,
            email_password TEXT NOT NULL,
            first_name TEXT NOT NULL
        )");
    }

    public function create(): void {}

    public static function getFields(): array
    {
        return [
            "Email",
            "Email Password",
            "First Name"
        ];        
    }

}