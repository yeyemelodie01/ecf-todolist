<?php

namespace App\Tests\Unit;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testSetAndGetTitle()
    {
        // Arrange
        $task = new Task();

        // Act
        $task->setTitle('Tâche importante');

        // Assert
        $this->assertEquals('Tâche importante', $task->getTitle());
    }


    public function testSetAndGetDueDate()
    {
        // Arrange
        $task = new Task();
        $date = new \DateTimeImmutable('2025-12-01');

        // Act
        $task->setDueDate($date);

        // Assert
        $this->assertSame($date, $task->getDueDate());
    }

    public function testSetAndGetDescription()
    {
        // Arrange
        $task = new Task();

        // Act
        $task->setDescription('Contenu de la tâche');

        // Assert
        $this->assertEquals('Contenu de la tâche', $task->getDescription());
    }

    public function testSetTitleToNull()
    {
        // Arrange
        $task = new Task();

        // Act
        $task->setTitle(null);

        // Assert
        $this->assertNull($task->getTitle());
    }

    public function testSetDueDateToInvalidValue()
    {
        $this->expectException(\TypeError::class);
        (new Task())->setDueDate('demain');
    }

}