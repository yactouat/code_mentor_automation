<?php

use Udacity\Database;
use Udacity\Models\Model;

final class DummyModel extends Model {

    protected string $tableName = "dummy";

    public function __construct()
    {
        parent::__construct();
    }

    public static function getCsvFields(): array
    {
        return [
            "Some Field"
        ];
    }

    public function persist(): void {}

    public static function validateInputFields(array $fields): array
    {
        return [];
    }
}