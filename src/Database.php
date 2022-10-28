<?php

namespace Udacity;

use PDO;
use PDOStatement;

final class Database {

    use LoggerTrait;

    private bool $isTesting;

    private ?PDO $databaseConn;

    /**
     * name of the database
     * 
     * is public because used in other namespaces (mainly abstract `Model` class)
     *
     * @var string
     */
    public static $dbName = "udacity_sl_automation";

    public function __construct(bool $isTesting = false)
    {
        $this->isTesting = !$isTesting ? ($_ENV["isTesting"] ?? false) : $isTesting;
        $this->_initConn();
        $this->setNewLogger(!$this->isTesting ? '/var/www/data/logs/php/db.log' : 
            '/var/www/tests/fixtures/logs/php/db.log'
        );
    }

    private function _closeConn(PDOStatement $statement): void {
        $statement = null;
        $this->databaseConn = null;
    }

    private function _getConn(): PDO {
        if (is_null($this->databaseConn)) {
            $this->_initConn();
        }
        return $this->databaseConn;
    }

    private function _initConn(): void {
        $this->databaseConn = new PDO(
            dsn: 'mysql:host=' . $_ENV['DB_HOST'] . ';port=' . $_ENV['DB_PORT'] . ';dbname=' . self::$dbName,
            username: $_ENV['DB_USER'],
            password: $_ENV['DB_PASSWORD'],
            options: [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
        );
    }

    private function _runQuery(string $sql, ?array $values = null): array {
        $this->startTimer();
        $statement = null;
        if (is_null($values)) {
            $statement = $this->_getConn()->query($sql);
        } else {
            $statement = $this->_getConn()->prepare($sql);
            $statement->execute(array_map(
                fn($val) => htmlspecialchars($val, ENT_QUOTES), 
                $values
            ));
        }
        $this->endTimer();
        $finalRes = $statement->fetchAll();
        $this->_closeConn($statement);
        return $finalRes;
    }

    public function readQuery(string $sql, ?array $values = null): array {
        $this->logger->info("running READ query : ".$sql);
        return $this->_runQuery($sql, $values);
    }

    public function writeQuery(string $sql, ?array $values = null): array {
        $this->logger->notice("running WRITE query : ".$sql);
        return $this->_runQuery($sql, $values);
    }

}