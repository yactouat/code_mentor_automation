<?php

namespace Udacity\Models;

use Udacity\Database;

final class SessionLeadModel extends Model {

    protected string $tableName = "sessionlead";

    public function __construct(private string $email, private string $email_password, private string $first_name)
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

    public function create(): void {
        $email = $this->email;
        $email_password = $this->email_password;
        $first_name = $this->first_name;
        $dbName = Database::$dbName;
        $tableName = $this->tableName;
        $query = "INSERT INTO $dbName.$tableName (email, email_password, first_name) VALUES ('$email', '$email_password', '$first_name')";
        $this->database->writeQuery($query);
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