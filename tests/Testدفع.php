<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\PaymentController;
use App\Repository\PaymentRepository;
use App\Entity\Payment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\QueryException;

class Testدفع extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;
    private $request;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(PaymentRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->request = $this->createMock(Request::class);

        $this->controller = new PaymentController($this->repository, $this->entityManager);
    }

    public function testGetPayments()
    {
        $payments = [new Payment()];
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($payments);

        $response = $this->controller->getPayments($this->request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetPaymentById()
    {
        $payment = new Payment();
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($payment);

        $response = $this->controller->getPayment(1, $this->request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetPaymentByIdNotFound()
    {
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->controller->getPayment(1, $this->request);
    }

    public function testCreatePayment()
    {
        $payment = new Payment();
        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($payment);
        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->request->expects($this->once())
            ->method('request')
            ->willReturn(['name' => 'test']);

        $response = $this->controller->createPayment($this->request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdatePayment()
    {
        $payment = new Payment();
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($payment);
        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->request->expects($this->once())
            ->method('request')
            ->willReturn(['name' => 'test']);

        $response = $this->controller->updatePayment(1, $this->request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testUpdatePaymentNotFound()
    {
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->controller->updatePayment(1, $this->request);
    }

    public function testDeletePayment()
    {
        $payment = new Payment();
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($payment);
        $this->entityManager->expects($this->once())
            ->method('remove')
            ->with($payment);
        $this->entityManager->expects($this->once())
            ->method('flush');

        $response = $this->controller->deletePayment(1, $this->request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeletePaymentNotFound()
    {
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->controller->deletePayment(1, $this->request);
    }
}


This test file covers the following scenarios:

- `testGetPayments`: Verifies that the `getPayments` method returns a response with a 200 status code when the `findAll` method of the repository returns a list of payments.
- `testGetPaymentById`: Verifies that the `getPayment` method returns a response with a 200 status code when the `find` method of the repository returns a payment.
- `testGetPaymentByIdNotFound`: Verifies that the `getPayment` method throws a `NotFoundHttpException` when the `find` method of the repository returns `null`.
- `testCreatePayment`: Verifies that the `createPayment` method returns a response with a 201 status code when the `persist` and `flush` methods of the entity manager are called.
- `testUpdatePayment`: Verifies that the `updatePayment` method returns a response with a 200 status code when the `find` method of the repository returns a payment and the `flush` method of the entity manager is called.
- `testUpdatePaymentNotFound`: Verifies that the `updatePayment` method throws a `NotFoundHttpException` when the `find` method of the repository returns `null`.
- `testDeletePayment`: Verifies that the `deletePayment` method returns a response with a 204 status code when the `find` method of the repository returns a payment and the `remove` and `flush` methods of the entity manager are called.
- `testDeletePaymentNotFound`: Verifies that the `deletePayment` method throws a `NotFoundHttpException` when the `find` method of the repository returns `null`.