<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\AppointmentsController;
use App\Repository\AppointmentsRepository;
use App\Service\AppointmentsService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TestAppointments extends TestCase
{
    private $appointmentsController;
    private $appointmentsRepository;
    private $appointmentsService;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(\PDO::class);
        $this->appointmentsRepository = $this->createMock(AppointmentsRepository::class);
        $this->appointmentsService = $this->createMock(AppointmentsService::class);
        $this->appointmentsController = new AppointmentsController($this->appointmentsRepository, $this->appointmentsService);
    }

    public function testGetAppointments()
    {
        $this->appointmentsRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                ['id' => 1, 'name' => 'John Doe', 'date' => '2022-01-01'],
                ['id' => 2, 'name' => 'Jane Doe', 'date' => '2022-01-02'],
            ]);

        $request = new Request();
        $response = $this->appointmentsController->getAppointments($request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(['appointments' => [
            ['id' => 1, 'name' => 'John Doe', 'date' => '2022-01-01'],
            ['id' => 2, 'name' => 'Jane Doe', 'date' => '2022-01-02'],
        ]], $response->getContent());
    }

    public function testCreateAppointment()
    {
        $this->appointmentsService->expects($this->once())
            ->method('createAppointment')
            ->with(['name' => 'John Doe', 'date' => '2022-01-01'])
            ->willReturn(['id' => 1, 'name' => 'John Doe', 'date' => '2022-01-01']);

        $request = new Request([], [], ['name' => 'John Doe', 'date' => '2022-01-01']);
        $response = $this->appointmentsController->createAppointment($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(['appointment' => ['id' => 1, 'name' => 'John Doe', 'date' => '2022-01-01']], $response->getContent());
    }

    public function testUpdateAppointment()
    {
        $this->appointmentsRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(['id' => 1, 'name' => 'John Doe', 'date' => '2022-01-01']);

        $this->appointmentsService->expects($this->once())
            ->method('updateAppointment')
            ->with(1, ['name' => 'John Doe', 'date' => '2022-01-01'])
            ->willReturn(['id' => 1, 'name' => 'John Doe', 'date' => '2022-01-01']);

        $request = new Request([], [], ['name' => 'John Doe', 'date' => '2022-01-01']);
        $response = $this->appointmentsController->updateAppointment(1, $request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(['appointment' => ['id' => 1, 'name' => 'John Doe', 'date' => '2022-01-01']], $response->getContent());
    }

    public function testDeleteAppointment()
    {
        $this->appointmentsRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(['id' => 1, 'name' => 'John Doe', 'date' => '2022-01-01']);

        $this->appointmentsService->expects($this->once())
            ->method('deleteAppointment')
            ->with(1);

        $request = new Request();
        $response = $this->appointmentsController->deleteAppointment(1, $request);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteAppointmentNotFound()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->appointmentsRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $request = new Request();
        $this->appointmentsController->deleteAppointment(1, $request);
    }
}


This test file covers the following scenarios:

1. `testGetAppointments`: Verifies that the `getAppointments` method returns a list of appointments.
2. `testCreateAppointment`: Verifies that the `createAppointment` method creates a new appointment.
3. `testUpdateAppointment`: Verifies that the `updateAppointment` method updates an existing appointment.
4. `testDeleteAppointment`: Verifies that the `deleteAppointment` method deletes an appointment.
5. `testDeleteAppointmentNotFound`: Verifies that the `deleteAppointment` method throws a `NotFoundHttpException` when the appointment is not found.

Note that this test file assumes that the `AppointmentsController` class has the following methods:

* `getAppointments(Request $request)`
* `createAppointment(Request $request)`
* `updateAppointment(int $id, Request $request)`
* `deleteAppointment(int $id, Request $request)`

Also, this test file assumes that the `AppointmentsRepository` class has the following methods:

* `findAll()`
* `find(int $id)`
* `delete(int $id)`

Finally, this test file assumes that the `AppointmentsService` class has the following methods:

* `createAppointment(array $data)`
* `updateAppointment(int $id, array $data)`
* `deleteAppointment(int $id)`