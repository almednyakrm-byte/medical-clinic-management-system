<?php

namespace App\Tests\Controller;

use App\Controller\AshraaController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class TestAshraa extends TestCase
{
    private $controller;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->controller = new AshraaController($this->pdoMock);
    }

    public function testGetAllAshraa()
    {
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM ashraa')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->controller->getAllAshraa();

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetAshraaById()
    {
        $id = 1;
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM ashraa WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);

        $response = $this->controller->getAshraaById($id);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCreateAshraa()
    {
        $data = ['name' => 'Test Ashraa'];
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO ashraa (name) VALUES (:name)')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('bindParam')
            ->with(':name', $data['name']);

        $response = $this->controller->createAshraa($data);

        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testUpdateAshraa()
    {
        $id = 1;
        $data = ['name' => 'Updated Test Ashraa'];
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE ashraa SET name = :name WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('bindParam')
            ->with(':name', $data['name']);
        $this->pdoMock->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);

        $response = $this->controller->updateAshraa($id, $data);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteAshraa()
    {
        $id = 1;
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM ashraa WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);

        $response = $this->controller->deleteAshraa($id);

        $this->assertEquals(200, $response->getStatusCode());
    }
}