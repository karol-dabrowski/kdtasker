<?php
declare(strict_types=1);

namespace Tasker\Model\Task\Event;

use Prooph\EventSourcing\AggregateChanged;
use Tasker\Model\Task\Domain\TaskId;

/**
 * Class TaskDeleted
 * @package Tasker\Model\Task\Event
 */
final class TaskDeleted extends AggregateChanged
{
	/**
	 * @var TaskId
	 */
	private $taskId;

	/**
	 * @param TaskId $taskId
	 * @return TaskDeleted
	 */
	public static function create(TaskId $taskId): TaskDeleted
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