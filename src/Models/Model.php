<?php

namespace App\Models;

use App\Database;

abstract class Model {

    protected Database $database;
    protected string $tableName;

    public abstract static function getFields(): array;

    public function __construct(?Database $database = null)
    {
        if (!isset($this->tableName)) {
            throw new \Exception("No table name set for this model", 1);
        }
        $this->database = is_null($database) ? new Database() : $database;
    }

    public function getTableName(): string {
        return $this->tableName;
    }

    public function selectAll(): array {
        return $this->database->readQuery("SELECT * FROM ".$this->tableName);
    }

}