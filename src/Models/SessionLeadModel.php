<?php

namespace Udacity\Models;

use Udacity\Database;
use Udacity\Emails\Mailer;

final class SessionLeadModel extends Model {

    protected string $tableName = "sessionlead";

    /**
     * creates a new instance of a session lead and creates related SQL table if not exists
     *
     * @param string $email
     * @param string $first_name
     * @param string $google_app_password
     * @param string $user_passphrase
     */
    public function __construct(
        private string $email, 
        private string $first_name,
        private string $google_app_password, 
        private string $user_passphrase
    )
    {
        parent::__construct();
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
        $sql = "INSERT INTO $dbName.$tableName (email, first_name, google_app_password, user_passphrase) 
            VALUES(?,?,?,?)";
        $this->database->writeQuery(
            $sql, 
            [
                $this->email, 
                $this->first_name, 
                password_hash($this->google_app_password, PASSWORD_DEFAULT), 
                password_hash($this->user_passphrase, PASSWORD_DEFAULT)
            ]
        );
        Mailer::buildMsmtprc($this->email, $this->google_app_password);
    }

    public function selectOneByEmail(string $email): array {
        if (!filter_var($email ?? '', FILTER_VALIDATE_EMAIL)) {
            return [];
        }
        $sql ='SELECT * FROM ' . $this->tableName . " WHERE email=?";
        $res = $this->database->readQuery($sql, [$email]);
        return $res[0] ?? [];
    }

    public static function validateInputFields(array $fields): array {
        $errors = [];
        if (empty($fields['email'])) {
            $errors[] = '📧 Your email address is missing';
        }
        if (empty($fields['first_name'])) {
            $errors[] = '❌ Your first name is missing';
        }
        if (empty($fields['google_app_password'])) {
            $errors[] = '🔑 Your Google application password is missing';
        }
        if (empty($fields['user_passphrase'])) {
            $errors[] = '🔑 Your user passphrase is missing';
        }
        if(!empty($fields['email']) && !filter_var($fields['email'] ?? '', FILTER_VALIDATE_EMAIL)) {
            $errors[] = '📧 Malformed email address';
        }
        if (count($errors) <= 0) {
            $sessionLead = new SessionLeadModel(
                email: $fields['email'], 
                first_name: $fields['first_name'], 
                google_app_password: $fields['google_app_password'],
                user_passphrase: $fields['user_passphrase']
            );
            if(count($sessionLead->selectOneByEmail($fields['email'])) > 0) {
                $errors[] = '📧 This email already exists in our system';
            }
        }
        return $errors;
    }

}