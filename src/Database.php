<?php

namespace App;

use PDO;

final class Database {

    private PDO $databaseConn;
    private static $sqliteDBPath = '/udacity_sl_automation/data/sql/database.db';

    public function __construct(?string $sqliteDBPath = null)
    {
        if(is_null($sqliteDBPath)) {
            $sqliteDBPath = self::$sqliteDBPath;
        }
        if (!file_exists($sqliteDBPath)) {
            fopen($sqliteDBPath, "w");
        }
        $this->databaseConn = new PDO('sqlite:'.$sqliteDBPath);
    }

    public function getDatabaseConn(): PDO {
        return $this->databaseConn;
    }

}