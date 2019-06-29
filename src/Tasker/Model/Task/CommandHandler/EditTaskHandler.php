<?php
declare(strict_types=1);

namespace Tasker\Model\Task\CommandHandler;

use Tasker\Model\Task\Command\EditTask;
use Tasker\Model\Task\Domain\TaskRepository;

/**
 * Class EditTaskHandler
 * @package Tasker\Model\Task\CommandHandler
 */
class EditTaskHandler
{
	/**
	 * @var TaskRepository
	 */
	private $tasks;

	/**
	 * EditTaskHandler constructor.
	 * @param TaskRepository $tasks
	 */
	public function __construct(TaskRepository $tasks)
	{
		$this->tasks = $tasks;
	}

	/**
	 * @param EditTask $command
	 * @throws \Exception
	 */
	public function __invoke(EditTask $command): void
	{
		$taskId = $command->taskId();
		$title = $command->title();
		$deadline = $command->deadline();
		$task = $this->tasks->get($taskId);

		if(!$task) {
			throw new \InvalidArgumentException('task|not_found');
		}

		$task->edit($title, $deadline);
		$this->tasks->save($task);
	}

}