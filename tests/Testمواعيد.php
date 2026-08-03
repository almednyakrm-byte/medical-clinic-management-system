<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\TimetableController;
use App\Repository\TimetableRepository;
use App\Entity\Timetable;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestTimetable extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(TimetableRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->controller = new TimetableController($this->repository, $this->entityManager);
    }

    public function testGetTimetables()
    {
        $timetables = [
            new Timetable(),
            new Timetable(),
        ];

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($timetables);

        $response = $this->controller->getTimetables();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testCreateTimetable()
    {
        $timetable = new Timetable();
        $timetable->setName('Test Timetable');

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($timetable);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $request = new Request([], [], ['timetable' => $timetable]);
        $response = $this->controller->createTimetable($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdateTimetable()
    {
        $timetable = new Timetable();
        $timetable->setName('Test Timetable');

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($timetable);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $request = new Request([], [], ['timetable' => $timetable]);
        $response = $this->controller->updateTimetable(1, $request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testDeleteTimetable()
    {
        $timetable = new Timetable();

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($timetable);

        $this->entityManager->expects($this->once())
            ->method('remove')
            ->with($timetable);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $response = $this->controller->deleteTimetable(1);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file covers the following scenarios:

- `testGetTimetables`: Tests the GET request to retrieve all timetables.
- `testCreateTimetable`: Tests the POST request to create a new timetable.
- `testUpdateTimetable`: Tests the PUT request to update an existing timetable.
- `testDeleteTimetable`: Tests the DELETE request to delete a timetable.

Each test method uses the `createMock` method to create a mock object for the `TimetableRepository` and `EntityManagerInterface` classes. The `expects` method is used to specify the expected behavior of the mock objects. The `willReturn` method is used to specify the return value of the mock objects.

The test methods also use the `Request` class to create a request object, and the `getStatusCode` and `headers` methods to verify the response status code and headers.

Note that this is just an example and you may need to modify the test file to fit your specific use case.