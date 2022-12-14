<?php

use Udacity\Services\DatabaseService;
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

    public static function getEmptyInstance(): self
    {
        return new self();
    }

    public function persist(): void {}

    public static function validateInputFields(array $fields): array
    {
        return [];
    }
}