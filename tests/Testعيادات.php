<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\عياداتController;
use App\Repository\عياداتRepository;
use App\Entity\عيادة;
use Doctrine\ORM\EntityManagerInterface;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Testعيادات extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;

    protected function setUp(): void
    {
        $this->repository = Mockery::mock(عياداتRepository::class);
        $this->entityManager = Mockery::mock(EntityManagerInterface::class);
        $this->controller = newعياداتController($this->repository, $this->entityManager);
    }

    public function testGetAll(): void
    {
        $this->repository->shouldReceive('findAll')->andReturn([newعيادة()]);
        $response = $this->controller->getAll();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetOne(): void
    {
        $id = 1;
        $this->repository->shouldReceive('find')->with($id)->andReturn(newعيادة());
        $response = $this->controller->getOne($id);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreate(): void
    {
        $request = new Request();
        $request->request->set('name', 'عيادة جديدة');
        $this->repository->shouldReceive('save')->with(Mockery::type(عيادة::class))->andReturn(newعيادة());
        $response = $this->controller->create($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdate(): void
    {
        $id = 1;
        $request = new Request();
        $request->request->set('name', 'عيادة محدثة');
        $this->repository->shouldReceive('find')->with($id)->andReturn(newعيادة());
        $this->repository->shouldReceive('save')->with(Mockery::type(عيادة::class))->andReturn(newعيادة());
        $response = $this->controller->update($id, $request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDelete(): void
    {
        $id = 1;
        $this->repository->shouldReceive('find')->with($id)->andReturn(newعيادة());
        $this->repository->shouldReceive('remove')->with(Mockery::type(عيادة::class));
        $response = $this->controller->delete($id);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


Note: This code assumes that the `عياداتController` class has methods for each CRUD operation, and that the `عياداتRepository` class has methods for interacting with the database. The `Mockery` library is used to create mock objects for the repository and entity manager.