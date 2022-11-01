<?php declare(strict_types=1);

namespace Tests\Integration\Apps\Web;

use PHPUnit\Framework\TestCase;
use Tests\Traits\TestsLoaderTrait;
use Udacity\Apps\CLI\CLIApp;
use Udacity\Exceptions\NoDBConnException;

final class CLIAppTest extends TestCase {

    use TestsLoaderTrait;

    protected function setUp(): void
    {
        $this->loadEnv();
    }

    public function testConstructWithNoDbConnThrows() {
        $this->resetWithBadDbHost();
        $this->expectException(NoDBConnException::class);
        $this->expectExceptionMessage('no database connectivity');
        $actual = new CLIApp('/var/www');
    }

}

