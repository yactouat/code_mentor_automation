<?php declare(strict_types=1);

namespace Tests\Unit\Traits;

use PHPUnit\Framework\TestCase;
use Udacity\Traits\ArraysTrait;

final class ArraysTraitTest extends TestCase {

    public function testArrayIsZeroIndexedOrderedListWithZeroIndexedOrderedListReturnsTrue() {
        $class = new class {
            use ArraysTrait;
        };
        $actual = $class->arrayIsZeroIndexedOrderedList([1,2,3]);
        $this->assertTrue($actual);
    }

    public function testArrayIsZeroIndexedOrderedListWithZeroIndexedOrderedListHavingGapsReturnsFalse() {
        $class = new class {
            use ArraysTrait;
        };
        $actual = $class->arrayIsZeroIndexedOrderedList([
            0 => '',
            1 => '',
            3 => ''
        ]);
        $this->assertFalse($actual);
    }

    public function testArrayIsZeroIndexedOrderedListWithZeroIndexedOrderedListHavingNonNumericKeysReturnsFalse() {
        $class = new class {
            use ArraysTrait;
        };
        $actual = $class->arrayIsZeroIndexedOrderedList([
            0 => '',
            1 => '',
            'a' => '',
            3 => '',
            'b' => ''
        ]);
        $this->assertFalse($actual);
    }

    public function testArrayIsZeroIndexedOrderedListWithKeyValueArrReturnsFalse() {
        $class = new class {
            use ArraysTrait;
        };
        $actual = $class->arrayIsZeroIndexedOrderedList([
            'a' => '',
            'b' => ''
        ]);
        $this->assertFalse($actual);
    }

    public function testArrayIsZeroIndexedOrderedListWithEmptyArrReturnsTrue() {
        $class = new class {
            use ArraysTrait;
        };
        $actual = $class->arrayIsZeroIndexedOrderedList([]);
        $this->assertTrue($actual);
    }

    public function testArrayIsZeroIndexedOrderedListWithArrayOfKeyValueArrsReturnsFalse() {
        $class = new class {
            use ArraysTrait;
        };
        $actual = $class->arrayIsZeroIndexedOrderedList([
            "key" => [
                "sub" => "val" 
            ],
            "key2" => [
                "sub2" => "val" 
            ],
        ]);
        $this->assertFalse($actual);
    }

}