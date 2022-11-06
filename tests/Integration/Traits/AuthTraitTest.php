<?php declare(strict_types=1);

namespace Tests\Integration\Traits;

use PHPUnit\Framework\TestCase;
use Udacity\Traits\AuthTrait;

final class AuthTraitTest extends TestCase {

    protected function tearDown(): void
    {
        // resetting session
        $_SESSION = [];
        // resetting test env
        $_ENV['IS_TESTING'] = true;
        $_ENV['APP_MODE'] = 'web';
        $_ENV['DB_HOST'] = 'mariadbtest';
        $_ENV['DB_PASSWORD'] = '';
        $_ENV['DB_PORT'] = 3306;
        $_ENV['DB_USER'] = 'root';
        $_ENV['ROOT_DIR'] = '/var/www';
    }

    public function testIsAuthedWithoutEnvSetAndWithAuthedUserReturnsTrue() {
        $expected = true;
        $_ENV = [];
        $_SESSION['authed'] = true;
        $instance = new class {
            use AuthTrait;
        };
        $actual = $instance->isAuthed();
        $this->assertEquals($expected, $actual);
    }

}