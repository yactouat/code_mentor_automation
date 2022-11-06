<?php

namespace Udacity\Models;

use Udacity\Services\DatabaseService;

/**
 * this class represents an online ressource that is shared with the students
 */
final class OnlineResourceModel extends Model {

    /**
     * {@inheritDoc}
     */
    protected string $tableName = "onlineresource";

    /**
     * {@inheritDoc}
     */
    public function __construct(private string $description, private string $name, private string $url)
    {
        parent::__construct();
    }

    /**
     * {@inheritDoc}
     */
    public static function getCsvFields(): array {
        return [
            "Name",
            "Description",
            "URL"
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function getEmptyInstance(): self
    {
        return new self(
            description: '',
            name: '',
            url: ''
        );        
    }

    /**
     * {@inheritDoc}
     */
    public function persist(): void {
        $description = $this->description;
        $name = $this->name;
        $url = $this->url;
        $dbName = DatabaseService::$dbName;
        $tableName = $this->tableName;
        $query = "INSERT INTO $dbName.$tableName (description, name, url) VALUES ('$description', '$name', '$url')";
        DatabaseService::getService('write_db')->{'writeQuery'}($query);
    }

    /**
     * {@inheritDoc}
     * 
     */
    public static function validateInputFields(array $fields): array
    {
        $errors = [];
        if(!empty($fields['onlineresourcescsv']['name']) && !in_array(
            strtolower(pathinfo($fields['onlineresourcescsv']['name'], PATHINFO_EXTENSION)),
            ['csv']
        )) {
            $errors[] = '📄 The uploaded file must be a CSV';
        }
        return $errors;
    }

}