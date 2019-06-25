<?php
declare(strict_types=1);

namespace Tasker\Model\Task\Event;

use Prooph\EventSourcing\AggregateChanged;
use Tasker\Model\Task\Domain\TaskId;

/**
 * Class TaskCompleted
 * @package Tasker\Model\Task\Event
 */
final class TaskCompleted extends AggregateChanged
{
	/**
	 * @var TaskId
	 */
	private $taskId;

	/**
	 * @param TaskId $taskId
	 * @return TaskCompleted
	 */
	public static function create(TaskId $taskId): TaskCompleted
	{
		$event = self::occur(
			$taskId->toString(),
			[]
		);

		$event->taskId = $taskId;
		return $event;
	}

	/**
	 * @return TaskId
	 */
	public function taskId(): TaskId
	{
		if (null === $this->taskId) {
			$this->taskId = TaskId::fromString($this->aggregateId());
		}

		return $this->taskId;
	}
}