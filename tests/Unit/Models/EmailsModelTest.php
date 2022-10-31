<?php declare(strict_types=1);

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use Udacity\Models\EmailsModel;

final class ModelTest extends TestCase {

    public function testDataFolderIsWritable() {
        $this->assertTrue(is_writable(EmailsModel::$dataFolder));
    }

}
