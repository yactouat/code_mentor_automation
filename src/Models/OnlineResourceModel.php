<?php

namespace Udacity\Models;

use Udacity\Database;

final class OnlineResourceModel extends Model {

    protected string $tableName = "onlineresource";

    public function __construct(private string $description, private string $name, private string $url)
    {
        parent::__construct();
        $dbName = Database::$dbName;
        $tableName = $this->tableName;
        $this->database->writeQuery("CREATE TABLE IF NOT EXISTS $dbName.$tableName(
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE,
            description TEXT NOT NULL,
            name TEXT NOT NULL,
            url TEXT NOT NULL UNIQUE
        )");
    }

    public static function getFields(): array {
        return [
            "Name",
            "Description",
            "URL"
        ];
    }

    public function persist(): void {
        $description = $this->description;
        $name = $this->name;
        $url = $this->url;
        $dbName = Database::$dbName;
        $tableName = $this->tableName;
        $query = "INSERT INTO $dbName.$tableName (description, name, url) VALUES ('$description', '$name', '$url')";
        $this->database->writeQuery($query);
    }

    public static function validateInputFields(array $fields): array
    {
        return [];
    }

}