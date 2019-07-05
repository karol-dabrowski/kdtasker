<?php
declare(strict_types=1);

namespace Tasker\Model\Task\Query;

use Tasker\Model\User\Domain\UserId;

/**
 * Class GetUserOpenTasksForNextDays
 * @package Tasker\Model\Task\Query
 */
class GetUserOpenTasksForNextDays
{
	/**
	 * @var UserId
	 */
	private $userId;

	/**
	 * @var int
	 */
	private $numberOfDays;

	/**
	 * GetUserOpenTasksForNextDays constructor.
	 * @param string $userId
	 * @param int $numberOfDays
	 */
	public function __construct(string $userId, int $numberOfDays = 7)
	{
		$this->userId = UserId::fromString($userId);
		$this->numberOfDays = $numberOfDays;
	}

	/**
	 * @return UserId
	 */
	public function userId(): UserId
	{
		return $this->userId;
	}

	/**
	 * @return int
	 */
	public function numberOfDays(): int
	{
		return $this->numberOfDays;
	}
}