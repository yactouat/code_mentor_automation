<?php

namespace Udacity\Models;

use Udacity\Database;
use Udacity\Emails\Mailer;

/**
 * this model represents a session lead, main actor of the app'
 */
final class SessionLeadModel extends Model {

    use ValidationTrait;

    /**
     * {@inheritDoc}
     */
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
     */
    public static function getCsvFields(): array
    {
        return [
            "Email",
            "First Name"
        ];        
    }

    /**
     * {@inheritDoc}
     */
    public static function getEmptyInstance(): self
    {
        return new self(
            email: '',
            first_name: '',
            google_app_password: '',
            user_passphrase: ''
        );        
    }

    /**
     * {@inheritDoc}
     * 
     * also creates a msmtprc file for the newly created session lead
     */
    public function persist(): void {
        if (count(self::validateInputFields([
            'email' => $this->email,
            'first_name' => $this->first_name,
            'google_app_password' => $this->google_app_password,
            'user_passphrase' => $this->user_passphrase
        ])) > 0) {
            return;
        }
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
        // setting the `msmtprc` config for the given user
        Mailer::buildMsmtprc($this->email, $this->google_app_password);
    }

    /**
     * gets a persisted session lead with his email
     *
     * @param string $email
     * @return array - empty if not found
     */
    public function selectOneByEmail(string $email): array {
        if (!self::validateEmail($email)) {
            return [];
        }
        $sql ='SELECT * FROM ' . $this->tableName . " WHERE email=?";
        $res = $this->database->readQuery($sql, [$email]);
        return $res[0] ?? [];
    }

    /**
     * {@inheritDoc}
     */
    public static function validateInputFields(array $fields): array {
        $errors = [];
        if (!self::validateEmail($fields['email'] ?? '')) {
            $errors[] = '📧 Malformed or missing email address';
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