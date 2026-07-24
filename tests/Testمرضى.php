<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\مرضىController;
use App\Repository\مرضىRepository;
use App\Entity\مرضى;
use App\Service\مرضىService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Testمرضى extends TestCase
{
    private $controller;
    private $repository;
    private $service;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock('PDO');
        $this->repository = $this->createMock(مرضىRepository::class);
        $this->service = $this->createMock(مرضىService::class);
        $this->controller = new مرضىController($this->repository, $this->service);
    }

    public function testGetAll()
    {
        $expectedData = ['مرضى1', 'مرضى2'];
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedData);
        $response = $this->controller->getAll();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($expectedData), $response->getContent());
    }

    public function testGetOne()
    {
        $expectedData = 'مرضى1';
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($expectedData);
        $response = $this->controller->getOne(1);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($expectedData), $response->getContent());
    }

    public function testGetOneNotFound()
    {
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);
        $this->controller->getOne(1);
    }

    public function testCreate()
    {
        $expectedData = 'مرضى1';
        $this->service->expects($this->once())
            ->method('create')
            ->with('مرضى1')
            ->willReturn($expectedData);
        $request = new Request();
        $request->request->set('name', 'مرضى1');
        $response = $this->controller->create($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(json_encode($expectedData), $response->getContent());
    }

    public function testUpdate()
    {
        $expectedData = 'مرضى1';
        $this->service->expects($this->once())
            ->method('update')
            ->with(1, 'مرضى1')
            ->willReturn($expectedData);
        $request = new Request();
        $request->request->set('name', 'مرضى1');
        $response = $this->controller->update(1, $request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($expectedData), $response->getContent());
    }

    public function testUpdateNotFound()
    {
        $this->expectException(NotFoundHttpException::class);
        $this->service->expects($this->once())
            ->method('update')
            ->with(1, 'مرضى1')
            ->willReturn(null);
        $request = new Request();
        $request->request->set('name', 'مرضى1');
        $this->controller->update(1, $request);
    }

    public function testDelete()
    {
        $this->service->expects($this->once())
            ->method('delete')
            ->with(1);
        $response = $this->controller->delete(1);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteNotFound()
    {
        $this->expectException(NotFoundHttpException::class);
        $this->service->expects($this->once())
            ->method('delete')
            ->with(1)
            ->willReturn(null);
        $this->controller->delete(1);
    }
}



// App\Controller\مرضىController.php

namespace App\Controller;

use App\Repository\مرضىRepository;
use App\Service\مرضىService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class مرضىController
{
    private $repository;
    private $service;

    public function __construct(مرضىRepository $repository, مرضىService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    public function getAll()
    {
        $data = $this->repository->findAll();
        return new Response(json_encode($data));
    }

    public function getOne($id)
    {
        $data = $this->repository->find($id);
        if (!$data) {
            throw new NotFoundHttpException('Not found');
        }
        return new Response(json_encode($data));
    }

    public function create(Request $request)
    {
        $data = $this->service->create($request->request->get('name'));
        return new Response(json_encode($data), Response::HTTP_CREATED);
    }

    public function update($id, Request $request)
    {
        $data = $this->service->update($id, $request->request->get('name'));
        if (!$data) {
            throw new NotFoundHttpException('Not found');
        }
        return new Response(json_encode($data));
    }

    public function delete($id)
    {
        $this->service->delete($id);
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}