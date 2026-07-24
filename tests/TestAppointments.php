<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\AppointmentsController;
use App\Repository\AppointmentsRepository;
use App\Service\AppointmentsService;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class TestAppointments extends TestCase
{
    private $controller;
    private $repository;
    private $service;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->repository = $this->createMock(AppointmentsRepository::class);
        $this->service = $this->createMock(AppointmentsService::class);
        $this->controller = new AppointmentsController($this->repository, $this->service);
    }

    public function testGetAppointments()
    {
        $appointments = [
            ['id' => 1, 'title' => 'Appointment 1'],
            ['id' => 2, 'title' => 'Appointment 2'],
        ];

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($appointments);

        $response = $this->controller->getAppointments();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(json_encode($appointments), $response->getBody()->getContents());
    }

    public function testCreateAppointment()
    {
        $appointment = ['id' => 1, 'title' => 'Appointment 1'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdo->expects($this->once())
            ->method('exec')
            ->with('INSERT INTO appointments (title) VALUES (:title)');
        $this->pdo->expects($this->once())
            ->method('lastInsertId')
            ->willReturn(1);

        $response = $this->controller->createAppointment($appointment);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals(json_encode($appointment), $response->getBody()->getContents());
    }

    public function testUpdateAppointment()
    {
        $appointment = ['id' => 1, 'title' => 'Appointment 1'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdo->expects($this->once())
            ->method('exec')
            ->with('UPDATE appointments SET title = :title WHERE id = :id');

        $response = $this->controller->updateAppointment($appointment);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(json_encode($appointment), $response->getBody()->getContents());
    }

    public function testDeleteAppointment()
    {
        $id = 1;
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdo->expects($this->once())
            ->method('exec')
            ->with('DELETE FROM appointments WHERE id = :id');

        $response = $this->controller->deleteAppointment($id);
        $this->assertEquals(200, $response->getStatusCode());
    }
}


This test class covers the basic CRUD operations for the 'appointments' module. It uses PHPUnit's mocking capabilities to simulate the behavior of the PDO statements and the repository and service classes. The tests verify that the controller returns the correct HTTP status codes and response bodies for each operation.