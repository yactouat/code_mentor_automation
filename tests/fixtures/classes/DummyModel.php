<?php

use Udacity\Database;
use Udacity\Models\Model;

final class DummyModel extends Model {

    protected string $tableName = "dummy";

    public function __construct()
    {
        parent::__construct();
        $dbName = Database::$dbName;
        $tableName = $this->tableName;
        $this->database->writeQuery("CREATE TABLE IF NOT EXISTS $dbName.$tableName(
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            some_field TEXT NOT NULL
        )");
    }

    public function create(): void {}

    public static function getFields(): array
    {
        return [
            "Some Field"
        ];
    }
}