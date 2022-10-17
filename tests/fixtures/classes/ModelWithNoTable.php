<?php

use Udacity\Models\Model;

final class ModelWithNoTable extends Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function persist(): void {}

    public static function getFields(): array
    {
        return [];
    }

    public static function validateInputFields(array $fields): array
    {
        return [];
    }
}