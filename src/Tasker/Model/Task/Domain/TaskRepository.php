<?php
declare(strict_types=1);

namespace Tasker\Model\Task\Domain;

/**
 * Interface TaskRepository
 * @package Tasker\Model\Task\Domain
 */
interface TaskRepository
{
	/**
	 * @param Task $task
	 */
	public function save(Task $task): void;

	/**
	 * @param TaskId $taskId
	 * @return Task|null
	 */
	public function get(TaskId $taskId): ?Task;
}