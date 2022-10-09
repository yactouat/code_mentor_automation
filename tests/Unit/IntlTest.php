<?php declare(strict_types=1);

namespace Tests\Unit;

use App\Intl;
use PHPUnit\Framework\TestCase;

final class IntlTest extends TestCase {

    public function testLanguageIsAllowedWithNonAllowedLangThrowsLanguageNotAllowedExeption() {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Allowed languages are `en` or `fr`");
        $actual = Intl::languageIsAllowed("zl");
    }

}