<?php

use Udacity\Models\Model;

final class ModelWithNoTable extends Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function persist(): void {}

    public static function getCsvFields(): array
    {
        return [];
    }

    public static function getEmptyInstance(): self
    {
        return new self();
    }

    public static function validateInputFields(array $fields): array
    {
        return [];
    }
}