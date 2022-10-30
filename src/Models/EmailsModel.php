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
    public function __construct(private string $sessreportcsv = '')
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
     * gets the Udacity session report CSV file name attached from which the batch of emails must be constructed
     *
     * @return string
     */
    public function getSessReportCsv() : string {
        return $this->sessreportcsv;
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
        if (empty($fields['type'])) {
            $errors[] = '📧 The email type is missing';
        }
        if (!empty($fields['type']) && !self::validateEmailType($fields['type'])) {
            $errors[] = '📧 Wrong email type, allowed types are ' . implode(' ', self::getValidEmailsTypes());
        }
        if (empty($fields['sessreportcsv'])) {
            $errors[] = '📄 You must upload a Udacity session report CSV';
        }
        if(!empty($fields['sessreportcsv']) && !in_array(
            strtolower(pathinfo($fields['sessreportcsv'], PATHINFO_EXTENSION)),
            ['csv']
        )) {
            $errors[] = '📄 The uploaded file must be a CSV';
        }
        return $errors;
    }

}