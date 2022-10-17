<?php declare(strict_types=1);

namespace Tests\Unit\Controllers;

use PHPUnit\Framework\TestCase;
use Udacity\Controllers\Resource\SessionLeadsController;

final class SessionLeadsControllerTest extends TestCase {

    protected function setUp(): void
    {
        $_POST = [];
    }

    public function testPersistWithNoSubmitFieldReturnsSignUpFormWithAnAlert() {
        $ctlr = new SessionLeadsController();
        $expected = str_replace(' ', '', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div>⚠️ Please send a valid form using the `submit` button</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>');
        $actual = str_replace(' ', '', $ctlr->persist());
        $this->assertTrue(str_contains($actual, $expected));
    }

    public function testPersistWithNoSubmitFieldSets400StatusCode() {
        $ctlr = new SessionLeadsController();
        $expected = 400;
        $ctlr->persist();
        $this->assertEquals($expected, $ctlr->getStatusCode());
    }

    public function testPersistWithNoErrorsKeepsOldUserInput() {
        $_POST["email"] = "john@doe.com";
        $ctlr = new SessionLeadsController();
        $expected = str_replace([' ', "\n"], ['', ''], '<input 
            type="email" 
            class="form-control" 
            id="email" 
            placeholder="Email address"
            value="john@doe.com"
            name="email"
        >');
        $actual = str_replace([' ', "\n"], ['', ''], $ctlr->persist());
        // $this->assertEquals($expected, $actual);
        $this->assertTrue(str_contains($actual, $expected));
    }

}