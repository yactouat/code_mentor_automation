<?php

namespace Udacity\Models;

use Udacity\Database;
use Udacity\Exceptions\SQLTableNotSetException;

/**
 * the parent class of all app's MVC models
 */
abstract class Model {

    /**
     * the database connection class to use
     *
     * @var Database
     */
    protected Database $database;

    /**
     * the SQL table name of the current instance
     *
     * @var string
     */
    protected string $tableName;

    /**
     * saves a record of the model in db
     *
     * @return void
     */
    public abstract function persist(): void;
    
    /**
     * getting Udacity CSV fields for the given model
     *
     * @return array
     */
    public abstract static function getCsvFields(): array;

    /**
     * gets an empty instance of the current model, useful to select records for instance
     *
     * @return self
     */
    public abstract static function getEmptyInstance(): self;

    /**
     * this function is responsible for the business logic validation of the instance model
     *
     * @param array $fields - a key value array of all the fields to parse
     * 
     * @return array - an array of validation errors
     */
    public abstract static function validateInputFields(array $fields): array;

    /**
     * finishes the construction of the instance model
     * 
     * checks if a SQL table exists for the given model and sets a db connection
     *
     * @param Database|null $database
     * 
     * @throws SQLTableNotSetException
     * 
     */
    protected function __construct(?Database $database = null)
    {
        if (!isset($this->tableName)) {
            throw new SQLTableNotSetException();
        }
        $this->database = is_null($database) ? new Database() : $database;
    }

    /**
     * gets the instance model SQL table name
     *
     * @return string
     */
    public function getTableName(): string {
        return $this->tableName;
    }

    /**
     * gets all db records of the given model
     *
     * @return array
     */
    public function selectAll(): array {
        return $this->database->readQuery("SELECT * FROM ".$this->tableName);
    }

}