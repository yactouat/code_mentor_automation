<?php

namespace App;

use PDO;

final class Database {

    use LoggerTrait;

    private bool $isTesting;

    private PDO $databaseConn;

    /**
     * name of the database
     * 
     * is public because used in other namespaces (mainly abstract `Model` class)
     *
     * @var string
     */
    public static $dbName = "udacity_sl_automation";

    private static $sqliteDBPath = '/udacity_sl_automation/data/sql/database.db';
    private static $sqliteTestDBPath = '/udacity_sl_automation/tests/fixtures/sql/database.db';

    public function __construct(?string $sqliteDBPath = null, bool $isTesting = false)
    {
        $this->isTesting = !$isTesting ? ($_ENV["isTesting"] ?? false) : $isTesting;
        $this->_setDbFilePath($sqliteDBPath);
        $this->_initConn();
        $this->_setDatabase();
        $this->setNewLogger(!$this->isTesting ? '/udacity_sl_automation/data/logs/php/db.log' : 
            '/udacity_sl_automation/tests/fixtures/logs/php/db.log');
    }

    private function _getConn(): PDO {
        return $this->databaseConn;
    }

    private function _initConn(): void {
        $this->databaseConn = new PDO(
            dsn: 'sqlite:'.self::$sqliteDBPath, 
            options: [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
        );
    }

    private function _setDatabase(): void {
        $dbPath = self::$sqliteDBPath;
        $dbName = self::$dbName;
        $this->databaseConn->query("ATTACH DATABASE '$dbPath' AS $dbName");
    }

    private function _setDbFilePath(?string $sqliteDBPath = null) : void {
        if(is_null($sqliteDBPath)) {
            $sqliteDBPath = !$this->isTesting ? self::$sqliteDBPath : self::$sqliteTestDBPath;
        } else {
            self::$sqliteDBPath = $sqliteDBPath;
        }
        if (!file_exists($sqliteDBPath)) {
            fopen($sqliteDBPath, "w");
        }
    }

    public function readQuery(string $query): array {
        $this->logger->info("running READ query : ".$query);
        $this->startTimer();
        $result = $this->databaseConn->query($query);
        $this->endTimer();
        return $result->fetchAll();
    }

    public function writeQuery(string $query): array {
        $this->logger->notice("running WRITE query : ".$query);
        $this->startTimer();
        $result = $this->databaseConn->query($query);
        $this->endTimer();
        return $result->fetchAll();
    }

}