<?php

namespace App\Models;

final class OnlineResourceModel extends Model {

    protected string $tableName = "onlineresource";

    public function __construct()
    {
        parent::__construct();
    }

    public static function getFields(): array {
        return [
            "Name",
            "Description",
            "URL"
        ];
    }

}