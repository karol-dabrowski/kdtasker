<?php
declare(strict_types=1);

namespace Tasker\Model\Task\Event;

use Prooph\EventSourcing\AggregateChanged;
use Tasker\Model\Task\Domain\TaskDeadline;
use Tasker\Model\Task\Domain\TaskId;

/**
 * Class TaskEdited
 * @package Tasker\Model\Task\Event
 */
final class TaskEdited extends AggregateChanged
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
	 * @var TaskDeadline
	 */
	private $deadline;

	/**
	 * @param TaskId $taskId
	 * @param string $title
	 * @param TaskDeadline $deadline
	 * @return TaskEdited
	 */
	public static function create(
		TaskId $taskId,
		string $title,
		TaskDeadline $deadline
	): TaskEdited
	{
		$event = self::occur(
			$taskId->toString(),
			[
				'title' => $title,
				'deadline_date' => $deadline->dateToString(),
				'deadline_time' => $deadline->timeToString()
			]
		);

		$event->taskId = $taskId;
		$event->title = $title;
		$event->deadline = $deadline;

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

	/**
	 * @return TaskDeadline
	 * @throws \Exception
	 */
	public function deadline(): TaskDeadline
	{
		if(null === $this->deadline) {
			$this->deadline = TaskDeadline::fromString($this->payload['deadline_date'], $this->payload['deadline_time']);
		}

		return $this->deadline;
	}
}