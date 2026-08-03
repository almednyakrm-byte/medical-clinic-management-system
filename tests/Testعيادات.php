<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class Testعيادات extends TestCase
{
    private $pdo;
    private $statement;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->statement = $this->createMock(PDOStatement::class);
    }

    public function testGetعيادات()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM عيادات')
            ->willReturn($this->statement);

        $this->statement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->statement->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'عيادة 1'],
                ['id' => 2, 'name' => 'عيادة 2'],
            ]);

        $result = $this->getعيادات();
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }

    public function testPostعيادة()
    {
        $data = ['name' => 'عيادة جديدة'];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO عيادات (name) VALUES (:name)')
            ->willReturn($this->statement);

        $this->statement->expects($this->once())
            ->method('bindParam')
            ->with(':name', $data['name']);

        $this->statement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->pdo->expects($this->once())
            ->method('lastInsertId')
            ->willReturn(1);

        $result = $this->postعيادة($data);
        $this->assertIsInt($result);
        $this->assertEquals(1, $result);
    }

    public function testPutعيادة()
    {
        $id = 1;
        $data = ['name' => 'عيادة محدثة'];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE عيادات SET name = :name WHERE id = :id')
            ->willReturn($this->statement);

        $this->statement->expects($this->once())
            ->method('bindParam')
            ->with(':name', $data['name']);

        $this->statement->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);

        $this->statement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $result = $this->putعيادة($id, $data);
        $this->assertIsBool($result);
        $this->assertTrue($result);
    }

    public function testDeleteعيادة()
    {
        $id = 1;

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM عيادات WHERE id = :id')
            ->willReturn($this->statement);

        $this->statement->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);

        $this->statement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $result = $this->deleteعيادة($id);
        $this->assertIsBool($result);
        $this->assertTrue($result);
    }

    private function getعيادات()
    {
        $stmt = $this->pdo->prepare('SELECT * FROM عيادات');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    private function postعيادة($data)
    {
        $stmt = $this->pdo->prepare('INSERT INTO عيادات (name) VALUES (:name)');
        $stmt->bindParam(':name', $data['name']);
        $stmt->execute();
        return $this->pdo->lastInsertId();
    }

    private function putعيادة($id, $data)
    {
        $stmt = $this->pdo->prepare('UPDATE عيادات SET name = :name WHERE id = :id');
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    private function deleteعيادة($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM عيادات WHERE id = :id');
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}