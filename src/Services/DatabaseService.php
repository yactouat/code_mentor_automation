<?php

namespace Udacity\Services;

use PDO;
use PDOException;
use PDOStatement;
use Udacity\Exceptions\NoDBConnException;
use Udacity\Services\LoggerService;
use Udacity\Services\ServicesContainer;

/**
 * this class is responsible for talking to a MariaDB/MySQL database
 */
final class DatabaseService extends ServicesContainer {

    /**
     * the nullable PDO object to connect to the db
     *
     * @var PDO|null
     */
    private ?PDO $databaseConn = null;

    /**
     * name of the database
     * 
     * is public because used in other namespaces (mainly abstract `Model` class)
     *
     * @var string
     */
    public static $dbName = "udacity_sl_automation";

    /**
     * the logger reference for this service
     *
     * @var string
     */
    private string $loggerName;

    /**
     * the database connection constructor
     * 
     * initialises the connection to the database and sets a logger
     * 
     * @throws NoDBConnException
     * 
     * TODO test db logger
     *
     */
    private function __construct()
    {
        $this->loggerName = $_ENV['IS_TESTING'] ? 'test_db_logger' : 'db_logger';
        if ($_ENV['DB_HOST'] !== 'unexistinghost') {
            $this->_initConn();
        }
        // throw app' specific exception if PDO instance is not set
        if (is_null($this->databaseConn)) {
            throw new NoDBConnException();
        }
    }

    /**
     * the database connection destructor
     */
    public function __destruct()
    {
        $this->closeConn();
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
     * @throws PDOException case connection fails
     * 
     * @return void
     */
    private function _initConn(): void {
        $logger = LoggerService::getService($this->loggerName);
        try {
            $this->databaseConn = new PDO(
                dsn: 'mysql:host=' . $_ENV['DB_HOST'] . ';port=' . $_ENV['DB_PORT'] . ';dbname=' . self::$dbName,
                username: $_ENV['DB_USER'],
                password: $_ENV['DB_PASSWORD'],
                options: [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
            );
        } catch (PDOException $pdoe) {
            $logger->{'critical'}($pdoe->getMessage());
        }
    }

    /**
     * runs a SQL query with or without variable parameters
     *
     * @param string $sql
     * @param array|null $values
     * @return array
     */
    private function _runQuery(string $sql, ?array $values = null): array {
        LoggerService::getService($this->loggerName)->{'startTimer'}();
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
        LoggerService::getService($this->loggerName)->{'endTimer'}();
        $finalRes = $statement->fetchAll();
        $this->closeConn($statement);
        return $finalRes;
    }

    /**
     * closes PDO connections
     *
     * @param PDOStatement $statement
     * @return void
     */
    public function closeConn(?PDOStatement $statement = null): void {
        $statement = null;
        $this->databaseConn = null;
    }

    /**
    * @inheritDoc
    */
    public static function getService(string $id): self {
        if(empty(self::$_instances[$id])) {
            self::$_instances[$id] = new self();  
        }
        return self::$_instances[$id];
    }

    /**
     * executes a SQL read query and logs it
     *
     * @param string $sql
     * @param array|null $values
     * 
     * TODO test that log message is written
     * 
     * @return array
     */
    public function readQuery(string $sql, ?array $values = null): array {
        LoggerService::getService($this->loggerName)->{'info'}("running READ query : ".$sql);
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
        LoggerService::getService($this->loggerName)->{'notice'}("running WRITE query : ".$sql);
        return $this->_runQuery($sql, $values);
    }

}