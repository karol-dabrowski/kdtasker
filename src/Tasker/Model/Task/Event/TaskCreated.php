<?php
declare(strict_types=1);

namespace Tasker\Model\Task\Event;

use Prooph\EventSourcing\AggregateChanged;
use Tasker\Model\Task\Domain\TaskDeadline;
use Tasker\Model\Task\Domain\TaskId;
use Tasker\Model\User\Domain\UserId;

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
	 * @var UserId
	 */
	private $creatorId;

	/**
	 * @var UserId
	 */
	private $assigneeId;

	/**
	 * @var TaskDeadline
	 */
	private $deadline;

	/**
	 * @param TaskId $taskId
	 * @param string $title
	 * @param UserId $creatorId
	 * @param UserId $assigneeId
	 * @param TaskDeadline $deadline
	 * @return TaskCreated
	 */
	public static function create(
		TaskId $taskId,
		string $title,
		UserId $creatorId,
		UserId $assigneeId,
		TaskDeadline $deadline
	): TaskCreated
	{
		$event = self::occur(
			$taskId->toString(),
			[
				'title' => $title,
				'creator_id' => $creatorId->toString(),
				'assignee_id' => $assigneeId->toString(),
				'deadline_date' => $deadline->dateToString(),
				'deadline_time' => $deadline->timeToString()
			]
		);

		$event->taskId = $taskId;
		$event->title = $title;
		$event->creatorId = $creatorId;
		$event->assigneeId = $assigneeId;
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
	 * @return UserId
	 */
	public function creatorId(): UserId
	{
		if (null === $this->creatorId) {
			$this->creatorId = UserId::fromString($this->payload['creator_id']);
		}

		return $this->creatorId;
	}

	/**
	 * @return UserId
	 */
	public function assigneeId(): UserId
	{
		if (null === $this->assigneeId) {
			$this->assigneeId = UserId::fromString($this->payload['assignee_id']);
		}

		return $this->assigneeId;
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