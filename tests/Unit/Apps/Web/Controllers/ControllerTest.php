<?php declare(strict_types=1);

namespace Tests\Unit\Apps\Web\Controllers;

require_once "/var/www/tests/fixtures/classes/DummyController.php";

use DummyController;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

final class ControllerTest extends TestCase {

    public function testConstructSetsRenderer() {
        $expected = Environment::class;
        $dummy = new DummyController();
        $actual = $dummy->getRenderer();
        $this->assertInstanceOf($expected, $actual);
    }

}