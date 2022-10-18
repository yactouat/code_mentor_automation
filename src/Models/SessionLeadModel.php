<?php

namespace Udacity\Models;

use Udacity\Database;

final class SessionLeadModel extends Model {

    protected string $tableName = "sessionlead";

    /**
     * creates a new instance of a session lead and creates related SQL table if not exists
     *
     * @param string $email
     * @param string $first_name
     * @param string $google_app_password
     * @param string $user_password
     */
    public function __construct(
        private string $email, 
        private string $first_name,
        private string $google_app_password, 
        private string $user_password
    )
    {
        parent::__construct();
        $dbName = Database::$dbName;
        $tableName = $this->tableName;
        $this->database->writeQuery("CREATE TABLE IF NOT EXISTS $dbName.$tableName(
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE,
            email TEXT NOT NULL UNIQUE,
            first_name TEXT NOT NULL,
            google_app_password TEXT NOT NULL,
            user_password VARCHAR(255) NOT NULL
        )");
    }

    /**
     * {@inheritDoc}
     * 
     * getting Udacity CSV fields for a session lead
     *
     * @return array
     */
    public static function getCsvFields(): array
    {
        return [
            "Email",
            "First Name"
        ];        
    }

    public function persist(): void {
        $dbName = Database::$dbName;
        $tableName = $this->tableName;
        $sql = "INSERT INTO $dbName.$tableName (email, first_name, google_app_password, user_password) 
            VALUES(?,?,?,?)";
        $this->database->writeQuery(
            $sql, 
            [$this->email, $this->first_name, $this->google_app_password, $this->user_password]
        );
    }

    public static function validateInputFields(array $fields): array {
        $errors = [];
        if (!isset($fields['submit'])) {
            $errors[] = '⚠️ Please send a valid form using the `submit` button';
        }
        if (empty($fields['email'])) {
            $errors[] = '📧 Your email address is missing';
        }
        if (empty($fields['first_name'])) {
            $errors[] = '❌ Your first name is missing';
        }
        if (empty($fields['google_app_password'])) {
            $errors[] = '🔑 Your Google application password is missing';
        }
        if(!filter_var($fields['email'] ?? '', FILTER_VALIDATE_EMAIL)) {
            $errors[] = '📧 Malformed email address';
        }
        return $errors;
    }

}