<?php declare(strict_types=1);

namespace App;

final class Intl {

    public static function languageIsAllowed(string $lang): void {
        if (!in_array($lang, ["en", "fr"])) {
            throw new \Exception("Allowed languages are `en` or `fr`", 1);
        }
    }

}