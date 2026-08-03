<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\تتبعController;
use App\Repository\تتبعRepository;
use App\Entity\تتبع;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class Testتتبع extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(تتبعRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->controller = new تتبعController($this->repository, $this->entityManager);
    }

    public function testGetAll(): void
    {
        $expectedResponse = new JsonResponse(['data' => []]);
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        $response = $this->controller->getAll();
        $this->assertEquals($expectedResponse, $response);
    }

    public function testGetOne(): void
    {
        $expectedResponse = new JsonResponse(['data' => []]);
        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 1])
            ->willReturn(new تتبع());

        $response = $this->controller->getOne(1);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testCreate(): void
    {
        $expectedResponse = new JsonResponse(['data' => []]);
        $this->repository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(تتبع::class));

        $request = new Request([], [], ['json' => ['name' => 'Test Name']]);
        $response = $this->controller->create($request);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdate(): void
    {
        $expectedResponse = new JsonResponse(['data' => []]);
        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 1])
            ->willReturn(new تتبع());

        $request = new Request([], [], ['json' => ['name' => 'Test Name']]);
        $response = $this->controller->update(1, $request);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testDelete(): void
    {
        $expectedResponse = new JsonResponse(['data' => []]);
        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 1])
            ->willReturn(new تتبع());

        $response = $this->controller->delete(1);
        $this->assertEquals($expectedResponse, $response);
    }
}



// App\Controller\تتبعController.php

namespace App\Controller;

use App\Repository\تتبعRepository;
use App\Entity\تتبع;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class تتبعController
{
    private $repository;
    private $entityManager;

    public function __construct(تتبعRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    public function getAll(): Response
    {
        $data = $this->repository->findAll();
        return new JsonResponse(['data' => $data]);
    }

    public function getOne(int $id): Response
    {
        $data = $this->repository->findOneBy(['id' => $id]);
        return new JsonResponse(['data' => $data]);
    }

    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $entity = new تتبع();
        $entity->setName($data['name']);
        $this->repository->save($entity);
        return new JsonResponse(['data' => $entity]);
    }

    public function update(int $id, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $entity = $this->repository->findOneBy(['id' => $id]);
        $entity->setName($data['name']);
        $this->repository->save($entity);
        return new JsonResponse(['data' => $entity]);
    }

    public function delete(int $id): Response
    {
        $entity = $this->repository->findOneBy(['id' => $id]);
        $this->repository->remove($entity);
        return new JsonResponse(['data' => $entity]);
    }
}