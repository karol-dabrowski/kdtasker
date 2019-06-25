<?php
declare(strict_types=1);

namespace Tasker\Model\Task\CommandHandler;

use Tasker\Model\Task\Command\FinishTask;
use Tasker\Model\Task\Domain\TaskRepository;

/**
 * Class FinishTaskHandler
 * @package Tasker\Model\Task\CommandHandler
 */
class FinishTaskHandler
{
	/**
	 * @var TaskRepository
	 */
	private $tasks;

	/**
	 * FinishTaskHandler constructor.
	 * @param TaskRepository $tasks
	 */
	public function __construct(TaskRepository $tasks)
	{
		$this->tasks = $tasks;
	}

	/**
	 * @param FinishTask $command
	 */
	public function __invoke(FinishTask $command): void
	{
		$taskId = $command->taskId();
		$task = $this->tasks->get($taskId);

		if(!$task) {
			throw new \InvalidArgumentException('task|not_found');
		}

		$task->complete();
		$this->tasks->save($task);
	}

}