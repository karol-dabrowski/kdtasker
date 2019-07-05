<?php
declare(strict_types=1);

namespace Tasker\Model\Task\QueryHandler;

use React\Promise\Deferred;
use Tasker\Infrastructure\Projection\Task\TaskFinder;
use Tasker\Model\Task\Query\GetUserOpenTasksForNextDays;

/**
 * Class GetUserOpenTasksForNextDaysHandler
 * @package Tasker\Model\Task\QueryHandler
 */
class GetUserOpenTasksForNextDaysHandler
{
	/**
	 * @var TaskFinder
	 */
	private $taskFinder;

	/**
	 * GetUserOpenTasksForNextDaysHandler constructor.
	 * @param TaskFinder $taskFinder
	 */
	public function __construct(TaskFinder $taskFinder)
	{
		$this->taskFinder = $taskFinder;
	}

	/**
	 * @param GetUserOpenTasksForNextDays $query
	 * @param Deferred|null $deferred
	 * @return array
	 * @throws \Exception
	 */
	public function __invoke(GetUserOpenTasksForNextDays $query, Deferred $deferred = null)
	{
		$userId = $query->userId();
		$numberOfDays = $query->numberOfDays();

		$tomorrow = new \DateTime();
		$tomorrow->modify('+1 day');
		$endDay = new \DateTime();
		$endDay->modify('+' . $numberOfDays . ' days');

		$response = $this->taskFinder->findUserOpenTasksBetweenDates($userId, $tomorrow, $endDay);

		if (null === $deferred) {
			return $response;
		}

		$deferred->resolve($response);
	}
}