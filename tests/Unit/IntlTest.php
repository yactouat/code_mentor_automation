<?php declare(strict_types=1);

namespace Tests\Unit;

use Udacity\Intl;
use PHPUnit\Framework\TestCase;
use Udacity\Exceptions\AllowedLanguageException;

final class IntlTest extends TestCase {

    public function testLanguageIsAllowedWithNonAllowedLangThrowsLanguageNotAllowedExeption() {
        $this->expectException(AllowedLanguageException::class);
        $this->expectExceptionMessage("allowed languages are `en` or `fr`");
        $actual = Intl::languageIsAllowed("zl");
    }

}