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
        $this->database->getConn()->query("CREATE TABLE IF NOT EXISTS $dbName.$tableName(
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            email TEXT NOT NULL,
            email_password TEXT NOT NULL,
            first_name TEXT NOT NULL
        )");
    }

    public static function getFields(): array
    {
        return [
            "Email",
            "Email Password",
            "First Name"
        ];        
    }

}