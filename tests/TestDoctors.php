<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Controller\DoctorController;
use App\Repository\DoctorRepository;
use App\Entity\Doctor;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\OptimisticLockException;

class TestDoctors extends WebTestCase
{
    private $client;
    private $entityManager;
    private $doctorRepository;
    private $doctorController;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->doctorRepository = $this->createMock(DoctorRepository::class);
        $this->doctorController = new DoctorController($this->doctorRepository);
    }

    public function testGetDoctors()
    {
        $this->doctorRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                new Doctor('John Doe', '1234567890'),
                new Doctor('Jane Doe', '9876543210'),
            ]);

        $response = $this->client->request('GET', '/doctors');
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetDoctorById()
    {
        $doctor = new Doctor('John Doe', '1234567890');
        $this->doctorRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($doctor);

        $response = $this->client->request('GET', '/doctors/1');
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testCreateDoctor()
    {
        $doctor = new Doctor('John Doe', '1234567890');
        $this->doctorRepository->expects($this->once())
            ->method('save')
            ->with($doctor)
            ->willReturn($doctor);

        $response = $this->client->request('POST', '/doctors', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => ['name' => 'John Doe', 'phone' => '1234567890'],
        ]);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdateDoctor()
    {
        $doctor = new Doctor('John Doe', '1234567890');
        $this->doctorRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($doctor);
        $this->doctorRepository->expects($this->once())
            ->method('save')
            ->with($doctor)
            ->willReturn($doctor);

        $response = $this->client->request('PUT', '/doctors/1', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => ['name' => 'Jane Doe', 'phone' => '9876543210'],
        ]);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testDeleteDoctor()
    {
        $this->doctorRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(new Doctor('John Doe', '1234567890'));

        $response = $this->client->request('DELETE', '/doctors/1');
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}



// DoctorController.php
namespace App\Controller;

use App\Repository\DoctorRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class DoctorController
{
    private $doctorRepository;

    public function __construct(DoctorRepository $doctorRepository)
    {
        $this->doctorRepository = $doctorRepository;
    }

    /**
     * @Route("/doctors", name="get_doctors", methods={"GET"})
     */
    public function getDoctors()
    {
        $doctors = $this->doctorRepository->findAll();
        return new JsonResponse($doctors);
    }

    /**
     * @Route("/doctors/{id}", name="get_doctor", methods={"GET"})
     */
    public function getDoctor($id)
    {
        $doctor = $this->doctorRepository->find($id);
        return new JsonResponse($doctor);
    }

    /**
     * @Route("/doctors", name="create_doctor", methods={"POST"})
     */
    public function createDoctor(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $doctor = new Doctor($data['name'], $data['phone']);
        $this->doctorRepository->save($doctor);
        return new JsonResponse($doctor, Response::HTTP_CREATED);
    }

    /**
     * @Route("/doctors/{id}", name="update_doctor", methods={"PUT"})
     */
    public function updateDoctor($id, Request $request)
    {
        $doctor = $this->doctorRepository->find($id);
        $data = json_decode($request->getContent(), true);
        $doctor->setName($data['name']);
        $doctor->setPhone($data['phone']);
        $this->doctorRepository->save($doctor);
        return new JsonResponse($doctor);
    }

    /**
     * @Route("/doctors/{id}", name="delete_doctor", methods={"DELETE"})
     */
    public function deleteDoctor($id)
    {
        $this->doctorRepository->find($id);
        $this->doctorRepository->remove($id);
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}