<?php
declare(strict_types=1);

namespace Tasker\Model\Task\Event;

use Prooph\EventSourcing\AggregateChanged;
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
	 * @param TaskId $taskId
	 * @param string $title
	 * @param UserId $creatorId
	 * @param UserId $assigneeId
	 * @return TaskCreated
	 */
	public static function create(TaskId $taskId, string $title, UserId $creatorId, UserId $assigneeId): TaskCreated
	{
		$event = self::occur(
			$taskId->toString(),
			[
				'title' => $title,
				'creator_id' => $creatorId->toString(),
				'assignee_id' => $assigneeId->toString()
			]
		);

		$event->taskId = $taskId;
		$event->title = $title;
		$event->creatorId = $creatorId;
		$event->assigneeId = $assigneeId;

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
}