<?php

namespace Udacity;

use PDO;
use PDOStatement;
use Udacity\Exceptions\WritePermissionException;

/**
 * this class is responsible for talking to a MariaDB/MySQL database
 */
final class Database {

    use LoggerTrait;

    /**
     * whether we are in testing environment or not
     * 
     * this conditions wiring to the test database
     *
     * @var boolean
     */
    private bool $isTesting;

    /**
     * the nullable PDO object to connect to the db
     *
     * @var PDO|null
     */
    private ?PDO $databaseConn;

    /**
     * name of the database
     * 
     * is public because used in other namespaces (mainly abstract `Model` class)
     *
     * @var string
     */
    public static $dbName = "udacity_sl_automation";

    /**
     * the database connection constructor
     * 
     * initialises the connection to the database and sets a logger 
     *
     * @param boolean $isTesting
     */
    public function __construct(bool $isTesting = false)
    {
        $this->isTesting = !$isTesting ? ($_ENV["isTesting"] ?? false) : $isTesting;
        $this->_initConn();
        $logsDir = !$this->isTesting ? '/var/www/data/logs/php/' : '/var/www/tests/fixtures/logs/php/';
        if (!is_writable($logsDir)) {
            throw new WritePermissionException();
        }
        $this->setNewLogger($logsDir . 'db.log');
    }

    /**
     * closes PDO connections
     *
     * @param PDOStatement $statement
     * @return void
     */
    private function _closeConn(PDOStatement $statement): void {
        $statement = null;
        $this->databaseConn = null;
    }

    /**
     * gets the current PDO connection
     * 
     * creates a new one if none existing
     *
     * @return PDO
     */
    private function _getConn(): PDO {
        if (is_null($this->databaseConn)) {
            $this->_initConn();
        }
        return $this->databaseConn;
    }

    /**
     * initializes a connection to the DB
     *
     * @return void
     */
    private function _initConn(): void {
        $this->databaseConn = new PDO(
            dsn: 'mysql:host=' . $_ENV['DB_HOST'] . ';port=' . $_ENV['DB_PORT'] . ';dbname=' . self::$dbName,
            username: $_ENV['DB_USER'],
            password: $_ENV['DB_PASSWORD'],
            options: [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
        );
    }

    /**
     * runs a SQL query with or without variable parameters
     *
     * @param string $sql
     * @param array|null $values
     * @return array
     */
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

    /**
     * executes a SQL read query and logs it
     *
     * @param string $sql
     * @param array|null $values
     * @return array
     */
    public function readQuery(string $sql, ?array $values = null): array {
        $this->logger->info("running READ query : ".$sql);
        return $this->_runQuery($sql, $values);
    }

    /**
     * executes a SQL write query and logs it
     *
     * @param string $sql
     * @param array|null $values
     * @return array
     */
    public function writeQuery(string $sql, ?array $values = null): array {
        $this->logger->notice("running WRITE query : ".$sql);
        return $this->_runQuery($sql, $values);
    }

}