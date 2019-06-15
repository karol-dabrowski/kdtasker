<?php
declare(strict_types=1);

namespace Tasker\Model\Task\QueryHandler;

use React\Promise\Deferred;
use Tasker\Infrastructure\Projection\Task\TaskFinder;
use Tasker\Model\Task\Query\GetUserTodaysTasks;

/**
 * Class GetUserTodaysTasksHandler
 * @package Tasker\Model\Task\QueryHandler
 */
class GetUserTodaysTasksHandler
{
	/**
	 * @var TaskFinder
	 */
	private $taskFinder;

	/**
	 * GetUserTodaysTasksHandler constructor.
	 * @param TaskFinder $taskFinder
	 */
	public function __construct(TaskFinder $taskFinder)
	{
		$this->taskFinder = $taskFinder;
	}

	/**
	 * @param GetUserTodaysTasks $query
	 * @param Deferred|null $deferred
	 * @return array|null
	 */
	public function __invoke(GetUserTodaysTasks $query, Deferred $deferred = null)
	{
		$userId = $query->userId();
		$day = date('Y-m-d');
		$response = $this->taskFinder->findExtendedUserTasksForSpecifiedDay($userId, $day);

		if (null === $deferred) {
			return $response;
		}

		$deferred->resolve($response);
	}
}