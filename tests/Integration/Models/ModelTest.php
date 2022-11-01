<?php declare(strict_types=1);

namespace Tests\Integration\Models;

require_once "/var/www/tests/fixtures/classes/ModelWithNoTable.php";
require_once "/var/www/tests/fixtures/classes/DummyModel.php";

use Udacity\Services\DatabaseService;
use DummyModel;
use ModelWithNoTable;
use PHPUnit\Framework\TestCase;
use Tests\Traits\TestsLoaderTrait;
use Udacity\Exceptions\SQLTableNotSetException;

final class ModelTest extends TestCase {

    use TestsLoaderTrait;

    protected function setUp(): void
    {
        $this->loadEnv();
    }

    protected function tearDown(): void
    {
        DatabaseService::getService('test_write_db')->{'writeQuery'}('TRUNCATE udacity_sl_automation.dummy');
    }

    public function testConstructWithNoTableNameSetThrows() {
        $this->expectException(SQLTableNotSetException::class);
        $this->expectExceptionMessage('the required SQL table does not exist');
        $model = new ModelWithNoTable();
    }

    public function testSelectAllWithRecordsInDbGetsAllRecords() {
        // arrange
        $expected = [
            [
                'id' => 1,
                'some_field' => 'test'
            ],
            [
                'id' => 2,
                'some_field' => 'test2'
            ],
            [
                'id' => 3,
                'some_field' => 'test3'
            ],
            [
                'id' => 4,
                'some_field' => 'test4'
            ]
        ];
        $dummy = new DummyModel();
        $dbName = DatabaseService::$dbName;
        DatabaseService::getService('test_write_db')->{'writeQuery'}("INSERT INTO $dbName.dummy (some_field) VALUES 
            ('test'),
            ('test2'),
            ('test3'),
            ('test4')
        ");
        // act
        $actual = $dummy->selectAll();
        // assert
        $this->assertEquals($expected, $actual);
    }

}
