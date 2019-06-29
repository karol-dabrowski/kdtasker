<?php
declare(strict_types=1);

namespace Tasker\Model\Task\Domain;

use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;
use Tasker\Model\Task\Event\TaskCompleted;
use Tasker\Model\Task\Event\TaskCreated;
use Tasker\Model\Task\Event\TaskDeleted;
use Tasker\Model\Task\Event\TaskEdited;
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
	 * @var TaskStatus
	 */
	private $status;

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
	 * @param string $title
	 * @param TaskDeadline $deadline
	 */
	public function edit(string $title, TaskDeadline $deadline): void
	{
		if($this->status !== TaskStatus::OPEN) {
			throw new \LogicException('task|cannot_be_edited');
		}

		$this->recordThat(TaskEdited::create($this->taskId, $title, $deadline));
	}

	public function complete(): void
	{
		if($this->status !== TaskStatus::OPEN) {
			throw new \LogicException('task|cannot_be_completed');
		}

		$this->recordThat(TaskCompleted::create($this->taskId));
	}

	public function delete(): void
	{
		if($this->status !== TaskStatus::OPEN) {
			throw new \LogicException('task|cannot_be_deleted');
		}

		$this->recordThat(TaskDeleted::create($this->taskId));
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
			case TaskEdited::class:
				$this->whenTaskEdited($event);
				break;
			case TaskCompleted::class:
				$this->whenTaskCompleted($event);
				break;
			case TaskDeleted::class:
				$this->whenTaskDeleted($event);
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
		$this->status = TaskStatus::OPEN;
	}

	/**
	 * @param TaskEdited $event
	 * @throws \Exception
	 */
	protected function whenTaskEdited(TaskEdited $event): void
	{
		$this->title = $event->title();
		$this->deadline = $event->deadline();
	}

	/**
	 * @param TaskCompleted $event
	 */
	protected function whenTaskCompleted(TaskCompleted $event): void
	{
		$this->status = TaskStatus::COMPLETED;
	}

	/**
	 * @param TaskDeleted $event
	 */
	protected function whenTaskDeleted(TaskDeleted $event): void
	{
		$this->status = TaskStatus::DELETED;
	}
}