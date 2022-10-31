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

    /**
     * {@inheritDoc}
     * 
     */
    public static function getEmptyInstance(): self
    {
        return new self();        
    }

    /**
     * gets the error message when the required email type is missing or wrong
     *
     * @return string
     */
    public static function getUnallowedEmailTypeErrorMess(): string {
        return '📧 Unallowed email type, allowed types are ' . implode(' ', self::getValidEmailsTypes());
    }

    /**
     * gets the array of valid emails types supported by the app'
     *
     * @return array
     */
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
     * validates an email type against the supported emails types of the app'
     *
     * @param string $type
     * @return boolean
     */
    public static function validateEmailType(string $type): bool {
        return in_array($type, self::getValidEmailsTypes());
    }

    /**
     * {@inheritDoc}
     * 
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