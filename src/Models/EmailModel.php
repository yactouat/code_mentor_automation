<?php

namespace Udacity\Models;

use Udacity\Database;

/**
 * this class represents an online ressource that is shared with the students
 */
final class EmailModel extends Model {

    /**
     * {@inheritDoc}
     * 
     * 
     * ! not implemented
     */
    protected string $tableName = "email";

    /**
     * {@inheritDoc}
     * 
     * ! not implemented
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * {@inheritDoc}
     * 
     * ! not implemented
     */
    public static function getCsvFields(): array {
        return [];
    }

    public static function getValidEmailsTypes(): array {
        return [
            'behind-students'
        ];
    }

    /**
     * {@inheritDoc}
     * 
     * ! not implemented
     */
    public function persist(): void {}

    /**
     * {@inheritDoc}
     * 
     * ! not implemented
     */
    public static function validateInputFields(array $fields): array
    {
        return [];
    }

}