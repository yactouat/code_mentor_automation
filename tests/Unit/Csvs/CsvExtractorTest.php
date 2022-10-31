<?php declare(strict_types=1);

namespace Tests\Unit\Csvs;

use PHPUnit\Framework\TestCase;
use Udacity\Csvs\CsvExtractor;
use Udacity\Exceptions\KeyValueArrayExpectedException;

final class CsvExtractorTest extends TestCase {

    public function testStringToCsvFileWithUniDimensionalListArrayThrows() {
        $this->expectException(KeyValueArrayExpectedException::class);
        $this->expectExceptionMessage('a key value array was expected');
        $actual = (new CsvExtractor())->stringToCsvFile([1,2,3]);
    }

    public function testStringToCsvFileWithTopLevelKeyValueArrThrows() {
        $this->expectException(KeyValueArrayExpectedException::class);
        $this->expectExceptionMessage('a key value array was expected');
        $actual = (new CsvExtractor())->stringToCsvFile([
            "key" => [
                "sub" => "val" 
            ],
            "key2" => [
                "sub2" => "val" 
            ],
        ]);
    }

    // public function testStringToCsvFileWithBiDimensionalArrContainingListsThrows() {
    //     $this->expectException(KeyValueArrayExpectedException::class);
    //     $this->expectExceptionMessage('a key value array was expected');
    //     $actual = (new CsvExtractor())->stringToCsvFile([
    //         0 => [0,1,2,3],
    //         1 => [0,1,2,3]
    //     ]);
    // }

    // public function testStringToCsvFileWithTopLevelListArrHavingGapsContainingListsThrows() {
    //     $this->expectException(KeyValueArrayExpectedException::class);
    //     $this->expectExceptionMessage('a key value array was expected');
    //     $actual = (new CsvExtractor())->stringToCsvFile([
    //         0 => [
    //             "sub" => "val" 
    //         ],
    //         1 => [
    //             "sub2" => "val" 
    //         ],
    //         3 => [
    //             "sub3" => "val" 
    //         ]
    //     ]);
    // }

    // public function testStringToCsvFileWithValidInputReturnsString() {
    //     $actual = (new CsvExtractor())->stringToCsvFile([
    //         0 => [
    //             "sub" => "val" 
    //         ],
    //         1 => [
    //             "sub" => "val2" 
    //         ],
    //         2 => [
    //             "sub" => "val3" 
    //         ]
    //     ]);
    //     $this->assertIsString($actual);
    // }

}

