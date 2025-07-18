<?php

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Entity\Task;
use App\Entity\User;
use App\Repository\TaskRepository;
use App\Service\TaskService;
use Doctrine\ORM\EntityManagerInterface;

class TaskServiceTest extends TestCase
{
    private $entityManager;
    private $taskRepository;
    private $taskService;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->taskRepository = $this->createStub(TaskRepository::class);

        $this->taskService = new TaskService(
            $this->taskRepository,
            $this->entityManager
        );
    }

    public function testCreateTaskAssignsUserAndPersists()
    {
        // Arrange
        $task = new Task();
        $user = new User();

        $this->entityManager->expects($this->once())->method('persist')->with($task);
        $this->entityManager->expects($this->once())->method('flush');

        // Act
        $this->taskService->createTask($task, $user);

        // Assert
        $this->assertSame($user, $task->getUser());
    }

    public function testDeleteTaskRemovesAndFlushes()
    {
        // Arrange
        $task = new Task();

        // Act & Assert
        $this->entityManager->expects($this->once())->method('remove')->with($task);
        $this->entityManager->expects($this->once())->method('flush');


        $this->taskService->deleteTask($task);
        $this->assertTrue(true);
    }

    public function testGetTasksForUserReturnsStubbedArray()
    {
        $user = new User();
        $expectedTasks = [new Task(), new Task()];

        $this->taskRepository->method('findBy')
            ->with(['user' => $user])
            ->willReturn($expectedTasks);

        $tasks = $this->taskService->getTasksForUser($user);
        $this->assertSame($expectedTasks, $tasks);
    }

    public function testGetTaskByIdReturnsNull()
    {
        $this->taskRepository->method('find')->with(999)->willReturn(null);
        $this->assertNull($this->taskService->getTaskById(999));
    }

    public function testUpdateTaskFlushes()
    {
        $this->entityManager->expects($this->once())->method('flush');
        $this->taskService->updateTask(new Task());
        $this->assertTrue(true);
    }
}