<?php declare(strict_types=1);

namespace Tests\Integration\Models;

use Udacity\Models\OnlineResourceModel;
use PHPUnit\Framework\TestCase;
use Tests\EnvLoaderTrait;

final class OnlineResourceModelTest extends TestCase {

    use EnvLoaderTrait;

    protected function setUp(): void
    {
        $this->loadEnv();
        $this->database->writeQuery('TRUNCATE udacity_sl_automation.onlineresource');
    }

    public function testPersistPersistsInstanceInDb() {
        // arrange
        $expected = [
            [
                "id" => 1,
                "description" => "test description",
                "name" => "test name",
                "url" => "test URL"
            ]
        ];   
        $onlineResource = new OnlineResourceModel("test description", "test name", "test URL");
        $onlineResource->persist();
        // act
        $actual = $onlineResource->selectAll();
        // assert
        $this->assertEquals($expected, $actual);     
    }

}