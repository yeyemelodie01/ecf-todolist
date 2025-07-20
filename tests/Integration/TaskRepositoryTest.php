<?php

namespace App\Tests\Integration;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $connection = $this->entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();
        $connection->executeStatement('DELETE FROM task');
        $connection->executeStatement('DELETE FROM user');
    }

    public function testPersistAndRetrieveTask()
    {
        $user = new User();
        $user->setEmail('test@test.com');
        $user->setPassword('hashed_password');
        $user->setRoles(['ROLE_USER']);
        $this->entityManager->persist($user);


        $task = new Task();
        $task->setTitle('Tâche test');
        $task->setDescription('Description test');
        $task->setUser($user);

        $this->entityManager->persist($task);
        $this->entityManager->flush();


        $repository = $this->entityManager->getRepository(Task::class);
        $retrieved = $repository->findOneBy(['title' => 'Tâche test']);

        $this->assertNotNull($retrieved);
        $this->assertSame('Tâche test', $retrieved->getTitle());
        $this->assertSame('Description test', $retrieved->getDescription());
        $this->assertSame($user->getEmail(), $retrieved->getUser()->getEmail());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        unset($this->entityManager);
    }
}