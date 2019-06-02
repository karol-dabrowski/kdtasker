<?php
declare(strict_types=1);

namespace Tasker\Model\Task\QueryHandler;

use React\Promise\Deferred;
use Tasker\Infrastructure\Projection\Task\TaskFinder;
use Tasker\Model\Task\Query\GetTasksByUserId;

/**
 * Class GetTasksByUserIdHandler
 * @package Tasker\Model\Task\QueryHandler
 */
class GetTasksByUserIdHandler
{
	/**
	 * @var TaskFinder
	 */
	private $taskFinder;

	/**
	 * GetTasksByUserIdHandler constructor.
	 * @param TaskFinder $taskFinder
	 */
	public function __construct(TaskFinder $taskFinder)
	{
		$this->taskFinder = $taskFinder;
	}

	/**
	 * @param GetTasksByUserId $query
	 * @param Deferred|null $deferred
	 * @return array|null
	 */
	public function __invoke(GetTasksByUserId $query, Deferred $deferred = null)
	{
		$response = $this->taskFinder->findUserTasks($query->userId());

		if (null === $deferred) {
			return $response;
		}

		$deferred->resolve($response);
	}
}