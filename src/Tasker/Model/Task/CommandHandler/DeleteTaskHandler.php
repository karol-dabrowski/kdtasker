<?php
declare(strict_types=1);

namespace Tasker\Model\Task\CommandHandler;

use Tasker\Model\Task\Command\DeleteTask;
use Tasker\Model\Task\Domain\TaskRepository;

/**
 * Class DeleteTaskHandler
 * @package Tasker\Model\Task\CommandHandler
 */
class DeleteTaskHandler
{
	/**
	 * @var TaskRepository
	 */
	private $tasks;

	/**
	 * DeleteTaskHandler constructor.
	 * @param TaskRepository $tasks
	 */
	public function __construct(TaskRepository $tasks)
	{
		$this->tasks = $tasks;
	}

	/**
	 * @param DeleteTask $command
	 */
	public function __invoke(DeleteTask $command): void
	{
		$taskId = $command->taskId();
		$task = $this->tasks->get($taskId);

		if(!$task) {
			throw new \InvalidArgumentException('task|not_found');
		}

		$task->delete();
		$this->tasks->save($task);
	}

}