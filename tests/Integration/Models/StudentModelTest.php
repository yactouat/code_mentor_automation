<?php declare(strict_types=1);

namespace Tests\Integration\Models;

use Udacity\Models\StudentModel;
use PHPUnit\Framework\TestCase;
use Tests\EnvLoaderTrait;

final class StudentModelTest extends TestCase {

    use EnvLoaderTrait;

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
                'email' => 'test email',
                'first_name' => 'test first name',
                'last_name' => 'test last name',
                'on_track_status' => 'Behind'
            ]
        ];   
        $sessionLead = new StudentModel('test email', 'test first name', 'test last name', 'Behind');
        $sessionLead->persist();
        // act
        $actual = $sessionLead->selectAll();
        // assert
        $this->assertEquals($expected, $actual);     
    }

    public function testPersistWithWrongOnTrackStatusDoesNotPersistInstanceInDb() {
        // arrange
        $expected = [];   
        $sessionLead = new StudentModel('test email', 'test first name', 'test last name', 'Somewhere');
        $sessionLead->persist();
        // act
        $actual = $sessionLead->selectAll();
        // assert
        $this->assertEquals($expected, $actual);     
    }

}