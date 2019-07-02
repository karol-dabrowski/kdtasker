<?php
declare(strict_types=1);

namespace Tasker\Model\Task\Query;

use Tasker\Model\Task\Domain\TaskId;
use Tasker\Model\User\Domain\UserId;

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
	 * @var UserId
	 */
	private $userId;

	/**
	 * GetTaskById constructor.
	 * @param string $taskId
	 * @param string $userId
	 */
	public function __construct(string $taskId, string $userId)
	{
		$this->taskId = TaskId::fromString($taskId);
		$this->userId = UserId::fromString($userId);
	}

	/**
	 * @return TaskId
	 */
	public function taskId(): TaskId
	{
		return $this->taskId;
	}

	/**
	 * @return UserId
	 */
	public function userId(): UserId
	{
		return $this->userId;
	}
}