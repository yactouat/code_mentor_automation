<?php declare(strict_types=1);

namespace Tests\Integration;

use Udacity\Database;
use PHPUnit\Framework\TestCase;
use Tests\EnvLoaderTrait;

final class DatabaseTest extends TestCase {

    use EnvLoaderTrait;

    protected function setUp(): void
    {
        $this->loadEnv();
    }

    public function testConstructCreatesDatabase() {
        $database = new Database();
        $expected = 'udacity_sl_automation';
        $result = $database->readQuery('SHOW DATABASES');
        $filtered = array_filter($result, function($db) use($expected) {
            return $db["Database"] === $expected;
            return $db;
        });
        $actual = array_pop($filtered);
        $this->assertEquals($expected, $actual["Database"]);
    }

}