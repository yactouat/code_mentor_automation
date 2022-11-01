<?php

namespace Udacity\Models;

use Udacity\Services\DatabaseService;

/**
 * this model represents a Udacity student in our business logic
 */
final class StudentModel extends Model {

    use ValidationTrait;

    /**
     * {@inheritDoc}
     */
    protected string $tableName = "student";

    /**
     * {@inheritDoc}
     *
     * @param string $email
     * @param string $first_name
     * @param string $last_name
     * @param string $on_track_status
     */
    public function __construct(
        private string $email, 
        private string $first_name,
        private string $last_name,
        private string $on_track_status
    )
    {
        parent::__construct();
    }

    /**
     * {@inheritDoc}
     */
    public static function getCsvFields(): array {
        return [
            "On-Track Status",
            "First Name",
            "Last Name",
            "Email"
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
            last_name: '',
            on_track_status: ''
        );        
    }

    /**
     * {@inheritDoc}
     * 
     * specific validation is set for the on-track status of the student (allowed values only)
     */
    public function persist(): void {
        if (
            count(self::validateInputFields(['on_track_status' => $this->on_track_status])) > 0
            || !self::validateEmail($this->email)
        ) {
            return;
        }
        $email = $this->email;
        $first_name = $this->first_name;
        $last_name = $this->last_name;
        $on_track_status = $this->on_track_status;
        $dbName = DatabaseService::$dbName;
        $tableName = $this->tableName;
        $query = "INSERT INTO $dbName.$tableName (email, first_name, last_name, on_track_status) VALUES 
            ('$email', '$first_name', '$last_name', '$on_track_status')";
        DatabaseService::getService('write_db')->{'writeQuery'}($query);
    }

    /**
     * {@inheritDoc}
     */
    public static function validateInputFields(array $fields): array
    {
        $errors = [];
        if (!in_array($fields['on_track_status'], ['Behind', 'On Track'])) {
            $errors[] = "🛤️ On track status can only be 'Behind' or 'On Track'";
        }
        return $errors;
    }

}