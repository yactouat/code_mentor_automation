<?php declare(strict_types=1);

namespace Tests\Integration\Models;

use PHPUnit\Framework\TestCase;
use Udacity\Models\EmailsModel;

final class EmailsModelTest extends TestCase {

    public function testDataFolderIsWritable() {
        $this->assertTrue(is_writable(EmailsModel::$dataFolder));
    }

}
