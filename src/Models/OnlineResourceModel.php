<?php

namespace App\Models;

final class OnlineResourceModel {

    public static function getFields(): array {
        return [
            "Name",
            "Description",
            "URL"
        ];
    }

}