<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\DoctorsController;
use App\Repository\DoctorsRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class TestDoctors extends TestCase
{
    private $controller;
    private $repository;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->repository = $this->createMock(DoctorsRepository::class);
        $this->controller = new DoctorsController($this->repository);
    }

    public function testGetDoctors(): void
    {
        $expectedResponse = ['doctors' => []];
        $this->repository->expects($this->once())
            ->method('getAll')
            ->willReturn($expectedResponse);

        $response = $this->controller->getDoctors();
        $this->assertEquals($expectedResponse, $response);
    }

    public function testPostDoctor(): void
    {
        $doctorData = ['name' => 'John Doe', 'specialty' => 'Cardiologist'];
        $expectedResponse = ['message' => 'Doctor created successfully'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO doctors (name, specialty) VALUES (:name, :specialty)');
        $this->pdo->expects($this->once())
            ->method('execute')
            ->with(['name' => $doctorData['name'], 'specialty' => $doctorData['specialty']]);

        $response = $this->controller->postDoctor($doctorData);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testPutDoctor(): void
    {
        $doctorId = 1;
        $doctorData = ['name' => 'John Doe', 'specialty' => 'Cardiologist'];
        $expectedResponse = ['message' => 'Doctor updated successfully'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE doctors SET name = :name, specialty = :specialty WHERE id = :id');
        $this->pdo->expects($this->once())
            ->method('execute')
            ->with(['name' => $doctorData['name'], 'specialty' => $doctorData['specialty'], 'id' => $doctorId]);

        $response = $this->controller->putDoctor($doctorId, $doctorData);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testDeleteDoctor(): void
    {
        $doctorId = 1;
        $expectedResponse = ['message' => 'Doctor deleted successfully'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM doctors WHERE id = :id');
        $this->pdo->expects($this->once())
            ->method('execute')
            ->with(['id' => $doctorId]);

        $response = $this->controller->deleteDoctor($doctorId);
        $this->assertEquals($expectedResponse, $response);
    }
}


This test file covers the CRUD operations for the 'doctors' module. It uses mocked PDO statements to simulate database interactions. The test methods cover the following scenarios:

- `testGetDoctors`: Tests the GET request to retrieve all doctors.
- `testPostDoctor`: Tests the POST request to create a new doctor.
- `testPutDoctor`: Tests the PUT request to update an existing doctor.
- `testDeleteDoctor`: Tests the DELETE request to delete a doctor.

Note that this is a basic implementation and you may need to modify it to fit your specific use case. Additionally, you will need to implement the `DoctorsController` and `DoctorsRepository` classes to make this test file work.