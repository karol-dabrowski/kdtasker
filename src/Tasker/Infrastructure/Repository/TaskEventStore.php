<?php
declare(strict_types=1);

namespace Tasker\Infrastructure\Repository;

use Prooph\EventSourcing\Aggregate\AggregateRepository;
use Tasker\Model\Task\Domain\Task;
use Tasker\Model\Task\Domain\TaskId;
use Tasker\Model\Task\Domain\TaskRepository;

/**
 * Class TaskEventStore
 * @package Tasker\Infrastructure\Repository
 */
class TaskEventStore extends AggregateRepository implements TaskRepository
{
	/**
	 * @param Task $task
	 */
	public function save(Task $task): void
	{
		$this->saveAggregateRoot($task);
	}

	/**
	 * @param TaskId $taskId
	 * @return Task|null
	 */
	public function get(TaskId $taskId): ?Task
	{
		return $this->getAggregateRoot($taskId->toString());
	}

}