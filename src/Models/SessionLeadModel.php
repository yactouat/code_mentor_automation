<?php

namespace App\Models;

final class SessionLeadModel extends Model {

    protected string $tableName = "sessionlead";

    public function __construct()
    {
        parent::__construct();
    }

    public static function getFields(): array
    {
        return [
            "First Name",
            "Email",
            "Email Password"
        ];        
    }

}