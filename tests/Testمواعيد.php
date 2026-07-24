<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\TimetableController;
use App\Repository\TimetableRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class TestTimetable extends TestCase
{
    private $controller;
    private $repository;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->repository = $this->createMock(TimetableRepository::class);
        $this->controller = new TimetableController($this->repository);
    }

    public function testGetTimetables()
    {
        $timetables = [
            ['id' => 1, 'name' => 'Timetable 1'],
            ['id' => 2, 'name' => 'Timetable 2'],
        ];

        $this->repository->expects($this->once())
            ->method('getAll')
            ->willReturn($timetables);

        $response = $this->controller->getTimetables();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(json_encode($timetables), $response->getBody()->getContents());
    }

    public function testCreateTimetable()
    {
        $timetable = ['name' => 'New Timetable'];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO timetables (name) VALUES (:name)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->pdo->expects($this->once())
            ->method('execute')
            ->with(['name' => $timetable['name']]);

        $response = $this->controller->createTimetable($timetable);

        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testUpdateTimetable()
    {
        $timetable = ['id' => 1, 'name' => 'Updated Timetable'];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE timetables SET name = :name WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->pdo->expects($this->once())
            ->method('execute')
            ->with(['name' => $timetable['name'], 'id' => $timetable['id']]);

        $response = $this->controller->updateTimetable($timetable);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteTimetable()
    {
        $id = 1;

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM timetables WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->pdo->expects($this->once())
            ->method('execute')
            ->with(['id' => $id]);

        $response = $this->controller->deleteTimetable($id);

        $this->assertEquals(200, $response->getStatusCode());
    }
}


This test file uses PHPUnit to test the CRUD API operations on the 'مواعيد' module. It creates a mock PDO object and a mock TimetableRepository object to simulate the database interactions. The test methods cover the GET, POST, PUT, and DELETE requests.