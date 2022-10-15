<?php declare(strict_types=1);

namespace Tests\Integration\Models;

use App\Models\Model;
use ModelWithNoTable;
use PHPUnit\Framework\TestCase;

require "/udacity_sl_automation/tests/fixtures/classes/ModelWithNoTable.php";

final class ModelTest extends TestCase {

    public function testConstructWithNoTableNameSetThrows() {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("No table name set for this model");
        $model = new ModelWithNoTable();
    }

}
