<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\ServicesController;
use App\Repository\ServicesRepository;
use App\Entity\Services;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class Testخدمات extends TestCase
{
    private $servicesController;
    private $servicesRepository;
    private $router;
    private $tokenStorage;

    protected function setUp(): void
    {
        $this->servicesRepository = $this->createMock(ServicesRepository::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);

        $this->servicesController = new ServicesController(
            $this->servicesRepository,
            $this->router,
            $this->tokenStorage
        );
    }

    public function testGetServices(): void
    {
        $expectedResponse = ['services' => []];

        $this->servicesRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        $response = $this->servicesController->getServices();

        $this->assertEquals($expectedResponse, $response);
    }

    public function testGetService(): void
    {
        $expectedResponse = ['service' => new Services()];

        $service = new Services();
        $this->servicesRepository
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($service);

        $response = $this->servicesController->getService(1);

        $this->assertEquals($expectedResponse, $response);
    }

    public function testGetServiceNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->servicesRepository
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->servicesController->getService(1);
    }

    public function testCreateService(): void
    {
        $expectedResponse = ['message' => 'Service created successfully'];

        $service = new Services();
        $this->servicesRepository
            ->expects($this->once())
            ->method('create')
            ->with($service)
            ->willReturn($service);

        $request = new Request([], [], ['service' => $service]);
        $response = $this->servicesController->createService($request);

        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdateService(): void
    {
        $expectedResponse = ['message' => 'Service updated successfully'];

        $service = new Services();
        $this->servicesRepository
            ->expects($this->once())
            ->method('update')
            ->with($service)
            ->willReturn($service);

        $request = new Request([], [], ['service' => $service]);
        $response = $this->servicesController->updateService(1, $request);

        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdateServiceNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->servicesRepository
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $request = new Request([], [], ['service' => new Services()]);
        $this->servicesController->updateService(1, $request);
    }

    public function testDeleteService(): void
    {
        $expectedResponse = ['message' => 'Service deleted successfully'];

        $this->servicesRepository
            ->expects($this->once())
            ->method('delete')
            ->with(1)
            ->willReturn(true);

        $response = $this->servicesController->deleteService(1);

        $this->assertEquals($expectedResponse, $response);
    }

    public function testDeleteServiceNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->servicesRepository
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->servicesController->deleteService(1);
    }
}


This test file uses PHPUnit to test the CRUD API operations on the 'خدمات' module. It uses mocked PDO statements to simulate database interactions. The tests cover the following scenarios:

*   `testGetServices`: Tests the `getServices` method to retrieve all services.
*   `testGetService`: Tests the `getService` method to retrieve a single service by ID.
*   `testGetServiceNotFound`: Tests the `getService` method to handle a non-existent service.
*   `testCreateService`: Tests the `createService` method to create a new service.
*   `testUpdateService`: Tests the `updateService` method to update an existing service.
*   `testUpdateServiceNotFound`: Tests the `updateService` method to handle a non-existent service.
*   `testDeleteService`: Tests the `deleteService` method to delete a service.
*   `testDeleteServiceNotFound`: Tests the `deleteService` method to handle a non-existent service.

Each test method uses the `createMock` method to create a mock object for the `ServicesRepository` class. The mock object is then configured to return specific values or throw exceptions as needed to simulate the expected behavior. The test methods then call the corresponding method on the `ServicesController` instance and assert that the response matches the expected value.