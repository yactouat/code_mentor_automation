<?php

namespace App\Models;

use App\Database;

final class OnlineResourceModel extends Model {

    protected string $tableName = "onlineresource";

    public function __construct()
    {
        parent::__construct();
        $dbName = Database::$dbName;
        $tableName = $this->tableName;
        $this->database->getConn()->query("CREATE TABLE IF NOT EXISTS $dbName.$tableName(
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            description TEXT NOT NULL,
            name TEXT NOT NULL,
            url TEXT NOT NULL
        )");
    }

    public static function getFields(): array {
        return [
            "Name",
            "Description",
            "URL"
        ];
    }

}