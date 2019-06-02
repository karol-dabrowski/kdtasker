<?php
declare(strict_types=1);

namespace Tasker\Model\Task\Query;

use Tasker\Model\User\Domain\UserId;

/**
 * Class GetTasksByUserId
 * @package Tasker\Model\Task\Query
 */
class GetTasksByUserId
{
	/**
	 * @var UserId
	 */
	private $userId;

	/**
	 * GetTasksByUserId constructor.
	 * @param string $userId
	 */
	public function __construct(string $userId)
	{
		$this->userId = UserId::fromString($userId);
	}

	/**
	 * @return UserId
	 */
	public function userId(): UserId
	{
		return $this->userId;
	}
}