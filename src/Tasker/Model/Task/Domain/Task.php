<?php
declare(strict_types=1);

namespace Tasker\Model\Task\Domain;

use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;
use Tasker\Model\Task\Event\TaskCreated;
use Tasker\Model\User\Domain\UserId;

/**
 * Class Task
 * @package Tasker\Model\Task\Domain
 */
class Task extends AggregateRoot
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
	 * @return Task
	 */
	public static function create(
		TaskId $taskId,
		string $title,
		UserId $creatorId,
		UserId $assigneeId,
		TaskDeadline $deadline
	): Task
	{
		$self = new self();
		$self->recordThat(TaskCreated::create($taskId, $title, $creatorId, $assigneeId, $deadline));

		return $self;
	}

	/**
	 * @return TaskId
	 */
	public function taskId(): TaskId
	{
		return $this->taskId;
	}

	/**
	 * @return string
	 */
	public function title(): string
	{
		return $this->title;
	}

	/**
	 * @return UserId
	 */
	public function creatorId(): UserId
	{
		return $this->creatorId;
	}

	/**
	 * @return UserId
	 */
	public function assigneeId(): UserId
	{
		return $this->assigneeId;
	}

	/**
	 * @return TaskDeadline
	 */
	public function deadline(): TaskDeadline
	{
		return $this->deadline;
	}

	/**
	 * @return string
	 */
	protected function aggregateId(): string
	{
		return $this->taskId->toString();
	}

	/**
	 * @param AggregateChanged $event
	 * @throws \Exception
	 */
	protected function apply(AggregateChanged $event): void
	{
		switch(get_class($event)) {
			case TaskCreated::class:
				$this->whenTaskCreated($event);
				break;
		}
	}

	/**
	 * @param TaskCreated $event
	 * @throws \Exception
	 */
	protected function whenTaskCreated(TaskCreated $event): void
	{
		$this->taskId = $event->taskId();
		$this->title = $event->title();
		$this->creatorId = $event->creatorId();
		$this->assigneeId = $event->assigneeId();
		$this->deadline = $event->deadline();
	}
}