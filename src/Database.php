<?php

namespace Udacity;

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

    public function __construct(bool $isTesting = false)
    {
        $this->isTesting = !$isTesting ? ($_ENV["isTesting"] ?? false) : $isTesting;
        $this->_initConn();
        $this->setNewLogger(!$this->isTesting ? '/var/www/data/logs/php/db.log' : 
            '/var/www/tests/fixtures/logs/php/db.log');
    }

    private function _getConn(): PDO {
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

    public function readQuery(string $query): array {
        $this->logger->info("running READ query : ".$query);
        $this->startTimer();
        $result = $this->databaseConn->query($query);
        $this->endTimer();
        return $result->fetchAll();
    }

    public function writeQuery(string $sql, ?array $values = null): array {
        $this->logger->notice("running WRITE query : ".$sql);
        $this->startTimer();
        $result = [];
        if (is_null($values)) {
            $executed = $this->databaseConn->query($sql);
            $result = $executed->fetchAll();
        } else {
            $statement = $this->databaseConn->prepare($sql);
            $statement->execute(array_map(
                fn($val) => htmlspecialchars($val, ENT_QUOTES), 
                $values)
            );
            $result = $statement->fetchAll();
        }
        $this->endTimer();
        return $result;
    }

}