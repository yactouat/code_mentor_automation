<?php declare(strict_types=1);

namespace Tests\Integration\Models;

use Udacity\Models\StudentModel;
use PHPUnit\Framework\TestCase;
use Tests\Integration\TestsLoaderTrait;

final class StudentModelTest extends TestCase {

    use TestsLoaderTrait;

    protected function setUp(): void
    {
        $this->loadEnv();
        $this->database->writeQuery('TRUNCATE udacity_sl_automation.student');
    }

    public function testPersistPersistsInstanceInDb() {
        // arrange
        $expected = [
            [
                'id' => 1,
                'email' => 'test@test.com',
                'first_name' => 'test first name',
                'last_name' => 'test last name',
                'on_track_status' => 'Behind'
            ]
        ];   
        $student = new StudentModel('test@test.com', 'test first name', 'test last name', 'Behind');
        $student->persist();
        // act
        $actual = $student->selectAll();
        // assert
        $this->assertEquals($expected, $actual);     
    }

    public function testPersistWithWrongOnTrackStatusDoesNotPersistInstanceInDb() {
        // arrange
        $expected = [];   
        $student = new StudentModel('test@test.com', 'test first name', 'test last name', 'Somewhere');
        $student->persist();
        // act
        $actual = $student->selectAll();
        // assert
        $this->assertEquals($expected, $actual);     
    }

    public function testPersistWithMalformedEmailDoesNotPersistInstanceInDb() {
        // arrange
        $expected = [];   
        $student = new StudentModel('test@.com', 'test first name', 'test last name', 'Behind');
        $student->persist();
        // act
        $actual = $student->selectAll();
        // assert
        $this->assertEquals($expected, $actual);     
    }

    public function testPersistWithNullEmailDoesNotPersistInstanceInDb() {
        // arrange
        $expected = [];   
        $student = new StudentModel('', 'test first name', 'test last name', 'Behind');
        $student->persist();
        // act
        $actual = $student->selectAll();
        // assert
        $this->assertEquals($expected, $actual);     
    }

}