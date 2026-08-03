<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\PatientRecordsRepository;
use App\Entity\PatientRecords;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class Testسجلات_مريض extends WebTestCase
{
    private $client;
    private $entityManager;
    private $patientRecordsRepository;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get('doctrine')->getManager();
        $this->patientRecordsRepository = $this->entityManager->getRepository(PatientRecords::class);
    }

    public function testGetAllPatientRecords()
    {
        $pdoMock = $this->createMock(PDO::class);
        $pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM patient_records')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->client->getContainer()->get('doctrine')->getConnection()->setMockConnection($pdoMock);

        $this->client->request('GET', '/api/سجلات_مريض');

        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testCreatePatientRecord()
    {
        $patientRecord = new PatientRecords();
        $patientRecord->setName('John Doe');
        $patientRecord->setAge(30);

        $pdoMock = $this->createMock(PDO::class);
        $pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO patient_records (name, age) VALUES (:name, :age)')
            ->willReturn($this->createMock(\PDOStatement::class));
        $pdoMock->expects($this->once())
            ->method('execute')
            ->with(['name' => 'John Doe', 'age' => 30]);

        $this->client->getContainer()->get('doctrine')->getConnection()->setMockConnection($pdoMock);

        $this->client->request(
            Request::METHOD_POST,
            '/api/سجلات_مريض',
            [],
            [],
            [],
            json_encode($patientRecord->toArray())
        );

        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testUpdatePatientRecord()
    {
        $patientRecord = new PatientRecords();
        $patientRecord->setId(1);
        $patientRecord->setName('John Doe');
        $patientRecord->setAge(30);

        $pdoMock = $this->createMock(PDO::class);
        $pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE patient_records SET name = :name, age = :age WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $pdoMock->expects($this->once())
            ->method('execute')
            ->with(['name' => 'John Doe', 'age' => 30, 'id' => 1]);

        $this->client->getContainer()->get('doctrine')->getConnection()->setMockConnection($pdoMock);

        $this->client->request(
            Request::METHOD_PUT,
            '/api/سجلات_مريض/1',
            [],
            [],
            [],
            json_encode($patientRecord->toArray())
        );

        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testDeletePatientRecord()
    {
        $pdoMock = $this->createMock(PDO::class);
        $pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM patient_records WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $pdoMock->expects($this->once())
            ->method('execute')
            ->with(['id' => 1]);

        $this->client->getContainer()->get('doctrine')->getConnection()->setMockConnection($pdoMock);

        $this->client->request(Request::METHOD_DELETE, '/api/سجلات_مريض/1');

        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file covers the following scenarios:

1.  **Get All Patient Records**: Tests the GET request to retrieve all patient records.
2.  **Create Patient Record**: Tests the POST request to create a new patient record.
3.  **Update Patient Record**: Tests the PUT request to update an existing patient record.
4.  **Delete Patient Record**: Tests the DELETE request to delete a patient record.

Each test case uses a mocked PDO connection to simulate database interactions. The `createMock` method is used to create mock objects for PDO and PDOStatement. The `expects` method is used to define the expected behavior of the mock objects.

Note that this is a basic example and you may need to modify it to fit your specific use case. Additionally, you should ensure that the `PatientRecords` entity and the `PatientRecordsRepository` are properly configured in your Symfony application.