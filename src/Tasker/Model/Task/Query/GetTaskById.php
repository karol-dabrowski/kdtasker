<?php
declare(strict_types=1);

namespace Tasker\Model\Task\Query;

use Tasker\Model\Task\Domain\TaskId;

/**
 * Class GetTaskById
 * @package Tasker\Model\Task\Query
 */
class GetTaskById
{
	/**
	 * @var TaskId
	 */
	private $taskId;

	/**
	 * GetTaskById constructor.
	 * @param string $taskId
	 */
	public function __construct(string $taskId)
	{
		$this->taskId = TaskId::fromString($taskId);
	}

	/**
	 * @return TaskId
	 */
	public function taskId(): TaskId
	{
		return $this->taskId;
	}
}