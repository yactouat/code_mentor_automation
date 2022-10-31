<?php

namespace Udacity\Models;

/**
 * this class represents a batch of emails to send to the students
 */
final class EmailsModel extends Model {

    /**
     * where the Udacity session reports CSV files will be stored
     */
    public static string $dataFolder = "/var/www/data/csv/";

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

    public static function getUnallowedEmailTypeErrorMess(): string {
        return '📧 Unallowed email type, allowed types are ' . implode(' ', self::getValidEmailsTypes());
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
    public function persist(): void {

    }

    public static function validateEmailType(string $type): bool {
        return in_array($type, self::getValidEmailsTypes());
    }

    /**
     * {@inheritDoc}
     * 
     * ! not implemented
     */
    public static function validateInputFields(array $fields): array
    {
        $errors = [];
        if (empty($fields['type']) || !self::validateEmailType($fields['type'])) {
            $errors[] = self::getUnallowedEmailTypeErrorMess();
        }
        if (empty($fields['sessreportcsv']['name'])) {
            $errors[] = '📄 You must upload a Udacity session report CSV';
        }
        if(!empty($fields['sessreportcsv']['name']) && !in_array(
            strtolower(pathinfo($fields['sessreportcsv']['name'], PATHINFO_EXTENSION)),
            ['csv']
        )) {
            $errors[] = '📄 The uploaded file must be a CSV';
        }
        return $errors;
    }

}