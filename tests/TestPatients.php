<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class TestPatients extends TestCase
{
    private $pdo;
    private $patientsController;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->patientsController = new PatientsController($this->pdo);
    }

    public function testGetAllPatients()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([]);

        $stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'John Doe'],
                ['id' => 2, 'name' => 'Jane Doe']
            ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM patients')
            ->willReturn($stmt);

        $response = $this->patientsController->getAllPatients();
        $this->assertIsArray($response);
        $this->assertCount(2, $response);
    }

    public function testGetPatientById()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1]);

        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'name' => 'John Doe']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM patients WHERE id = ?')
            ->willReturn($stmt);

        $response = $this->patientsController->getPatientById(1);
        $this->assertIsArray($response);
        $this->assertEquals(1, $response['id']);
    }

    public function testCreatePatient()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['John Doe', 'john@example.com']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO patients (name, email) VALUES (?, ?)')
            ->willReturn($stmt);

        $response = $this->patientsController->createPatient(['name' => 'John Doe', 'email' => 'john@example.com']);
        $this->assertTrue($response);
    }

    public function testUpdatePatient()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['John Doe', 'john@example.com', 1]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE patients SET name = ?, email = ? WHERE id = ?')
            ->willReturn($stmt);

        $response = $this->patientsController->updatePatient(1, ['name' => 'John Doe', 'email' => 'john@example.com']);
        $this->assertTrue($response);
    }

    public function testDeletePatient()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM patients WHERE id = ?')
            ->willReturn($stmt);

        $response = $this->patientsController->deletePatient(1);
        $this->assertTrue($response);
    }
}

class PatientsController
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllPatients()
    {
        $stmt = $this->pdo->prepare('SELECT * FROM patients');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getPatientById($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM patients WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function createPatient($data)
    {
        $stmt = $this->pdo->prepare('INSERT INTO patients (name, email) VALUES (?, ?)');
        return $stmt->execute($data);
    }

    public function updatePatient($id, $data)
    {
        $stmt = $this->pdo->prepare('UPDATE patients SET name = ?, email = ? WHERE id = ?');
        return $stmt->execute(array_merge($data, [$id]));
    }

    public function deletePatient($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM patients WHERE id = ?');
        return $stmt->execute([$id]);
    }
}