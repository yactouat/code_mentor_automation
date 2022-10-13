<?php

namespace App;

use PDO;

final class Database {

    private PDO $databaseConn;
    private static string $sqliteDBPath = 'sqlite:/udacity_sl_automation/data/sql/database.db';

    public function __construct()
    {
        $this->databaseConn = new PDO(self::$sqliteDBPath);
    }

    public function getDatabaseConn(): PDO {
        return $this->databaseConn;
    }

}