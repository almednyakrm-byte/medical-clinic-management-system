<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\مرضىController;
use App\Repository\مرضىRepository;
use App\Entity\مرضى;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use PHPUnit\Framework\MockObject\MockObject;

class Testمرضى extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;
    private $router;
    private $tokenStorage;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(مرضىRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);

        $this->controller = new مرضىController($this->repository, $this->entityManager, $this->router, $this->tokenStorage);
    }

    public function testGetAll()
    {
        $expectedResponse = [
            ['id' => 1, 'name' => 'مرضى 1'],
            ['id' => 2, 'name' => 'مرضى 2'],
        ];

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedResponse);

        $response = $this->controller->getAll();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testGetOne()
    {
        $expectedResponse = ['id' => 1, 'name' => 'مرضى 1'];

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($expectedResponse);

        $response = $this->controller->getOne(1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testPost()
    {
        $data = ['name' => 'مرضى 3'];

        $this->repository->expects($this->once())
            ->method('save')
            ->with($this->callback(function (مرضى $entity) use ($data) {
                return $entity->getName() === $data['name'];
            }));

        $response = $this->controller->post($data);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testPut()
    {
        $data = ['name' => 'مرضى 1'];

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(['id' => 1, 'name' => 'مرضى 1']);

        $this->repository->expects($this->once())
            ->method('save')
            ->with($this->callback(function (مرضى $entity) use ($data) {
                return $entity->getName() === $data['name'];
            }));

        $response = $this->controller->put(1, $data);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDelete()
    {
        $this->repository->expects($this->once())
            ->method('remove')
            ->with(1);

        $response = $this->controller->delete(1);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}



// App\Controller\مرضىController.php

namespace App\Controller;

use App\Repository\مرضىRepository;
use App\Entity\مرضى;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class مرضىController
{
    private $repository;
    private $entityManager;
    private $router;
    private $tokenStorage;

    public function __construct(
        مرضىRepository $repository,
        EntityManagerInterface $entityManager,
        RouterInterface $router,
        TokenStorageInterface $tokenStorage
    ) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->tokenStorage = $tokenStorage;
    }

    public function getAll()
    {
        $data = $this->repository->findAll();
        return new Response(json_encode($data), Response::HTTP_OK);
    }

    public function getOne($id)
    {
        $data = $this->repository->find($id);
        return new Response(json_encode($data), Response::HTTP_OK);
    }

    public function post($data)
    {
        $entity = new مرضى();
        $entity->setName($data['name']);
        $this->repository->save($entity);
        return new Response('', Response::HTTP_CREATED);
    }

    public function put($id, $data)
    {
        $entity = $this->repository->find($id);
        $entity->setName($data['name']);
        $this->repository->save($entity);
        return new Response('', Response::HTTP_OK);
    }

    public function delete($id)
    {
        $this->repository->remove($id);
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}