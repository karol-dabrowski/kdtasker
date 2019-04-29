<?php
declare(strict_types=1);

namespace App\Tests\Unit\Task;

use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;
use Tasker\Model\Task\Domain\TaskId;

/**
 * Class TaskIdTest
 * @package App\Tests
 */
class TaskIdTest extends \Codeception\Test\Unit
{
	public function testTaskIdCanBeGenerated()
	{
		$taskId = TaskId::generate();
		$this->assertInstanceOf(TaskId::class, $taskId);

		$taskIdString = $taskId->toString();
		$this->assertIsString($taskIdString);
		$this->assertSame(36, strlen($taskIdString));
	}

	public function testTaskIdCanBeCreatedFromString()
	{
		$taskIdString = Uuid::uuid4()->toString();

		$taskId = TaskId::fromString($taskIdString);
		$this->assertInstanceOf(TaskId::class, $taskId);
		$this->assertIsString($taskId->toString());
		$this->assertSame($taskIdString, $taskId->toString());
	}

	public function testUserIdThrowsExceptionWhenCreatingFromIncorrectString()
	{
		$invalidUuidString = 'Invalid-UUID-string';
		$this->expectException(InvalidUuidStringException::class);
		TaskId::fromString($invalidUuidString);
	}

	public function testTaskIdCanBeCompared()
	{
		$baseTaskId = TaskId::generate();
		$sameTaskId = $baseTaskId;
		$otherTaskId = TaskId::generate();

		$this->assertTrue($baseTaskId->isEqual($sameTaskId));
		$this->assertFalse($baseTaskId->isEqual($otherTaskId));
	}
}