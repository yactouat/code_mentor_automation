<?php declare(strict_types=1);

namespace Udacity;

/**
 * this class is responsible for managing the internationalization of the app'
 */
final class Intl {

    /**
     * checks if input language is allowed in the app'
     *
     * @param string $lang - currently supported languages are `en` `fr`
     * 
     * @throws Exception if language is not allowed
     * 
     * @return void
     */
    public static function languageIsAllowed(string $lang): void {
        if (!in_array($lang, ["en", "fr"])) {
            throw new \Exception("Allowed languages are `en` or `fr`", 1);
        }
    }

}