<?php
declare(strict_types=1);

namespace Tasker\Model\Task\Domain;

use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;
use Tasker\Model\Task\Event\TaskWasCreated;

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
	 * @param TaskId $taskId
	 * @param string $title
	 * @return Task
	 */
	public static function create(TaskId $taskId, string $title): Task
	{
		$self = new self();
		$self->recordThat(TaskWasCreated::create($taskId, $title));

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
	 * @return string
	 */
	protected function aggregateId(): string
	{
		return $this->taskId->toString();
	}

	/**
	 * @param AggregateChanged $event
	 */
	protected function apply(AggregateChanged $event): void
	{
		switch(get_class($event)) {
			case TaskWasCreated::class:
				$this->whenTaskWasCreated($event);
				break;
		}
	}

	/**
	 * @param TaskWasCreated $event
	 */
	protected function whenTaskWasCreated(TaskWasCreated $event): void
	{
		$this->taskId = $event->taskId();
		$this->title = $event->title();
	}
}