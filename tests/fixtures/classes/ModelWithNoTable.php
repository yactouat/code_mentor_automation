<?php

use App\Models\Model;

final class ModelWithNoTable extends Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function create(): void {}

    public static function getFields(): array
    {
        return [];
    }
}