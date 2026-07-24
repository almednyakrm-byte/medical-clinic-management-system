<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\PatientsController;
use App\Repository\PatientRepository;
use App\Entity\Patient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TestPatients extends TestCase
{
    private $patientsController;
    private $patientRepository;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock('PDO');
        $this->patientRepository = $this->createMock(PatientRepository::class);
        $this->patientsController = new PatientsController($this->patientRepository);
    }

    public function testGetPatients()
    {
        $patients = [
            new Patient('John Doe', 'john@example.com'),
            new Patient('Jane Doe', 'jane@example.com'),
        ];

        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM patients')
            ->willReturn($this->createMock('PDOStatement'));

        $this->patientRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($patients);

        $response = $this->patientsController->getPatients();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($patients), $response->getContent());
    }

    public function testGetPatient()
    {
        $patient = new Patient('John Doe', 'john@example.com');

        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM patients WHERE id = ?', [1])
            ->willReturn($this->createMock('PDOStatement'));

        $this->patientRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($patient);

        $response = $this->patientsController->getPatient(1);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($patient), $response->getContent());
    }

    public function testGetPatientNotFound()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM patients WHERE id = ?', [1])
            ->willReturn($this->createMock('PDOStatement'));

        $this->patientRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->patientsController->getPatient(1);
    }

    public function testCreatePatient()
    {
        $patient = new Patient('John Doe', 'john@example.com');

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO patients (name, email) VALUES (?, ?)')
            ->willReturn($this->createMock('PDOStatement'));

        $this->pdoMock->expects($this->once())
            ->method('commit');

        $this->patientRepository->expects($this->once())
            ->method('create')
            ->with($patient)
            ->willReturn($patient);

        $request = new Request([], [], ['name' => 'John Doe', 'email' => 'john@example.com']);
        $response = $this->patientsController->createPatient($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(json_encode($patient), $response->getContent());
    }

    public function testUpdatePatient()
    {
        $patient = new Patient('John Doe', 'john@example.com');

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE patients SET name = ?, email = ? WHERE id = ?')
            ->willReturn($this->createMock('PDOStatement'));

        $this->pdoMock->expects($this->once())
            ->method('commit');

        $this->patientRepository->expects($this->once())
            ->method('update')
            ->with($patient)
            ->willReturn($patient);

        $request = new Request([], [], ['name' => 'John Doe', 'email' => 'john@example.com']);
        $response = $this->patientsController->updatePatient(1, $request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($patient), $response->getContent());
    }

    public function testDeletePatient()
    {
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM patients WHERE id = ?')
            ->willReturn($this->createMock('PDOStatement'));

        $this->pdoMock->expects($this->once())
            ->method('commit');

        $response = $this->patientsController->deletePatient(1);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}