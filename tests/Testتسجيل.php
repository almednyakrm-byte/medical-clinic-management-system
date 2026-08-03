<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\تسجيلController;
use App\Repository\تسجيلRepository;
use App\Entity\تسجيل;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PHPUnit\Framework\MockObject\MockObject;

class Testتسجيل extends TestCase
{
    private $controller;
    private $repository;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock('PDO');
        $this->repository = $this->createMock(تسجيلRepository::class);
        $this->controller = new تسجيلController($this->repository);
    }

    public function testGetAll()
    {
        $expectedResponse = ['data' => []];
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedResponse['data']);

        $response = $this->controller->getAll();
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    public function testGetOne()
    {
        $expectedResponse = ['data' => []];
        $id = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($expectedResponse['data']);

        $response = $this->controller->getOne($id);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    public function testCreate()
    {
        $expectedResponse = ['data' => []];
        $data = ['name' => 'test', 'email' => 'test@example.com'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($this->createMock('PDOStatement'));
        $this->pdo->expects($this->once())
            ->method('exec')
            ->with('INSERT INTO تسجيل (name, email) VALUES (:name, :email)');
        $this->repository->expects($this->once())
            ->method('save')
            ->with($data);

        $response = $this->controller->create($data);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    public function testUpdate()
    {
        $expectedResponse = ['data' => []];
        $id = 1;
        $data = ['name' => 'test', 'email' => 'test@example.com'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($this->createMock('PDOStatement'));
        $this->pdo->expects($this->once())
            ->method('exec')
            ->with('UPDATE تسجيل SET name = :name, email = :email WHERE id = :id');
        $this->repository->expects($this->once())
            ->method('update')
            ->with($id, $data);

        $response = $this->controller->update($id, $data);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    public function testDelete()
    {
        $expectedResponse = ['data' => []];
        $id = 1;
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($this->createMock('PDOStatement'));
        $this->pdo->expects($this->once())
            ->method('exec')
            ->with('DELETE FROM تسجيل WHERE id = :id');
        $this->repository->expects($this->once())
            ->method('delete')
            ->with($id);

        $response = $this->controller->delete($id);
        $this->assertEquals($expectedResponse, $response->getContent());
    }
}



// App\Controller\تسجيلController.php
namespace App\Controller;

use App\Repository\تسجيلRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class تسجيلController
{
    private $repository;

    public function __construct(تسجيلRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        $data = $this->repository->findAll();
        return new Response(json_encode(['data' => $data]));
    }

    public function getOne($id)
    {
        $data = $this->repository->find($id);
        return new Response(json_encode(['data' => $data]));
    }

    public function create(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $this->repository->save($data);
        return new Response(json_encode(['data' => $data]));
    }

    public function update($id, Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $this->repository->update($id, $data);
        return new Response(json_encode(['data' => $data]));
    }

    public function delete($id)
    {
        $this->repository->delete($id);
        return new Response(json_encode(['data' => []]));
    }
}



// App\Repository\تسجيلRepository.php
namespace App\Repository;

use App\Entity\تسجيل;

class تسجيلRepository
{
    public function findAll()
    {
        // Implement logic to retrieve all records from database
    }

    public function find($id)
    {
        // Implement logic to retrieve a record by ID from database
    }

    public function save($data)
    {
        // Implement logic to save a new record to database
    }

    public function update($id, $data)
    {
        // Implement logic to update a record in database
    }

    public function delete($id)
    {
        // Implement logic to delete a record from database
    }
}



// App\Entity\تسجيل.php
namespace App\Entity;

class تسجيل
{
    private $id;
    private $name;
    private $email;

    // Getters and setters
}