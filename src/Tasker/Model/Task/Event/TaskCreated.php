<?php
declare(strict_types=1);

namespace Tasker\Model\Task\Event;

use Prooph\EventSourcing\AggregateChanged;
use Tasker\Model\Task\Domain\TaskId;

/**
 * Class TaskCreated
 * @package Tasker\Model\Task\Event
 */
final class TaskCreated extends AggregateChanged
{
	/**
	 * @var TaskId
	 */
	private $taskId;

	/**
	 * @var string
	 */
	private $title;

	/**
	 * @param TaskId $taskId
	 * @param string $title
	 *
	 * @return TaskCreated
	 */
	public static function create(TaskId $taskId, string $title ): TaskCreated
	{
		$event = self::occur(
			$taskId->toString(),
			[
				'title' => $title
			]
		);

		$event->taskId = $taskId;
		$event->title = $title;

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

	/**
	 * @return string
	 */
	public function title(): string
	{
		if (null === $this->title) {
			$this->title = $this->payload['title'];
		}

		return $this->title;
	}
}