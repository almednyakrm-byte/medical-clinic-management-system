<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\MedicalRecordsController;
use App\Repository\MedicalRecordsRepository;
use App\Entity\MedicalRecord;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\QueryException;

class TestMedicalRecords extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(MedicalRecordsRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->controller = new MedicalRecordsController($this->repository, $this->entityManager);
    }

    public function testGetMedicalRecords()
    {
        $expectedResponse = new JsonResponse(['medical_records' => []]);
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([]);
        $response = $this->controller->getMedicalRecords();
        $this->assertEquals($expectedResponse, $response);
    }

    public function testGetMedicalRecordById()
    {
        $id = 1;
        $expectedResponse = new JsonResponse(['medical_record' => new MedicalRecord()]);
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(new MedicalRecord());
        $response = $this->controller->getMedicalRecord($id);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testCreateMedicalRecord()
    {
        $medicalRecord = new MedicalRecord();
        $expectedResponse = new JsonResponse(['message' => 'Medical record created successfully']);
        $this->repository->expects($this->once())
            ->method('save')
            ->with($medicalRecord);
        $response = $this->controller->createMedicalRecord($medicalRecord);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdateMedicalRecord()
    {
        $id = 1;
        $medicalRecord = new MedicalRecord();
        $expectedResponse = new JsonResponse(['message' => 'Medical record updated successfully']);
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($medicalRecord);
        $this->repository->expects($this->once())
            ->method('save')
            ->with($medicalRecord);
        $response = $this->controller->updateMedicalRecord($id, $medicalRecord);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testDeleteMedicalRecord()
    {
        $id = 1;
        $expectedResponse = new JsonResponse(['message' => 'Medical record deleted successfully']);
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(new MedicalRecord());
        $this->repository->expects($this->once())
            ->method('remove')
            ->with(new MedicalRecord());
        $response = $this->controller->deleteMedicalRecord($id);
        $this->assertEquals($expectedResponse, $response);
    }
}



// MedicalRecordsController.php

namespace App\Controller;

use App\Repository\MedicalRecordsRepository;
use App\Entity\MedicalRecord;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;

class MedicalRecordsController
{
    private $repository;
    private $entityManager;

    public function __construct(MedicalRecordsRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    public function getMedicalRecords()
    {
        $medicalRecords = $this->repository->findAll();
        return new JsonResponse(['medical_records' => $medicalRecords]);
    }

    public function getMedicalRecord($id)
    {
        $medicalRecord = $this->repository->find($id);
        return new JsonResponse(['medical_record' => $medicalRecord]);
    }

    public function createMedicalRecord(MedicalRecord $medicalRecord)
    {
        $this->repository->save($medicalRecord);
        return new JsonResponse(['message' => 'Medical record created successfully']);
    }

    public function updateMedicalRecord($id, MedicalRecord $medicalRecord)
    {
        $existingMedicalRecord = $this->repository->find($id);
        $existingMedicalRecord->setAttributes($medicalRecord->getAttributes());
        $this->repository->save($existingMedicalRecord);
        return new JsonResponse(['message' => 'Medical record updated successfully']);
    }

    public function deleteMedicalRecord($id)
    {
        $medicalRecord = $this->repository->find($id);
        $this->repository->remove($medicalRecord);
        return new JsonResponse(['message' => 'Medical record deleted successfully']);
    }
}



// MedicalRecordsRepository.php

namespace App\Repository;

use App\Entity\MedicalRecord;
use Doctrine\ORM\EntityManagerInterface;

class MedicalRecordsRepository
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findAll()
    {
        return $this->entityManager->getRepository(MedicalRecord::class)->findAll();
    }

    public function find($id)
    {
        return $this->entityManager->getRepository(MedicalRecord::class)->find($id);
    }

    public function save(MedicalRecord $medicalRecord)
    {
        $this->entityManager->persist($medicalRecord);
        $this->entityManager->flush();
    }

    public function remove(MedicalRecord $medicalRecord)
    {
        $this->entityManager->remove($medicalRecord);
        $this->entityManager->flush();
    }
}



// MedicalRecord.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class MedicalRecord
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     */
    private $description;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }
}