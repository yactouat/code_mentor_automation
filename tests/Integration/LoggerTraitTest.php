<?php declare(strict_types=1);

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;
use Udacity\LoggerTrait;

final class LoggerTraitTest extends TestCase {

    protected function setUp(): void
    {
        $_ENV = [];
    }

    public function testgetLogsDirWithoutTestingEnvReturnsCorrectLogsDir() {
        $expected = '/var/www/data/logs/php/';
        $instance = new class {
            use LoggerTrait;
        };
        $actual = $instance->getLogsDir();
        $this->assertEquals($expected, $actual);
    }

    public function testgetLogsDirWithTestingEnvReturnsCorrectLogsDir() {
        $_ENV['IS_TESTING'] = true;
        $expected = '/var/www/tests/fixtures/logs/php/';
        $instance = new class {
            use LoggerTrait;
        };
        $actual = $instance->getLogsDir();
        $this->assertEquals($expected, $actual);
    }


}