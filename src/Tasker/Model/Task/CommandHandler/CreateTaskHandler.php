<?php
declare(strict_types=1);

namespace Tasker\Model\Task\CommandHandler;

use Tasker\Model\Task\Command\CreateTask;
use Tasker\Model\Task\Domain\Task;
use Tasker\Model\Task\Domain\TaskRepository;

/**
 * Class CreateTaskHandler
 * @package Tasker\Model\Task\CommandHandler
 */
class CreateTaskHandler
{
	/**
	 * @var TaskRepository
	 */
	private $tasks;

	/**
	 * CreateTaskHandler constructor.
	 * @param TaskRepository $tasks
	 */
	public function __construct(TaskRepository $tasks)
	{
		$this->tasks = $tasks;
	}

	/**
	 * @param CreateTask $command
	 */
	public function __invoke(CreateTask $command): void
	{
		$task = Task::create(
			$command->taskId(),
			$command->title()
		);

		$this->tasks->save($task);
	}

}