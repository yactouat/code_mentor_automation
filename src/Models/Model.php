<?php

namespace App\Models;

use App\Database;

abstract class Model {

    private Database $database;
    protected string $tableName;

    public abstract static function getFields(): array;

    public function __construct(?Database $database = null)
    {
        if (!isset($this->tableName)) {
            throw new \Exception("No table name set for this model", 1);
        }
        $dbName = Database::$dbName;
        $tableName = $this->tableName;
        $this->database = is_null($database) ? new Database() : $database;
        $this->database->getConn()->query("CREATE TABLE IF NOT EXISTS $dbName.$tableName(
            id INTEGER PRIMARY KEY AUTOINCREMENT
        )");
    }

    public function getTableName(): string {
        return $this->tableName;
    }

}