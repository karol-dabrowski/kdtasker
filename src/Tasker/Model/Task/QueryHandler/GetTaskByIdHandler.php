<?php
declare(strict_types=1);

namespace Tasker\Model\Task\QueryHandler;

use React\Promise\Deferred;
use Tasker\Infrastructure\Projection\Task\TaskFinder;
use Tasker\Model\Task\Query\GetTaskById;

/**
 * Class GetTaskByIdHandler
 * @package Tasker\Model\Task\QueryHandler
 */
class GetTaskByIdHandler
{
	/**
	 * @var TaskFinder
	 */
	private $taskFinder;

	/**
	 * GetTaskByIdHandler constructor.
	 * @param TaskFinder $taskFinder
	 */
	public function __construct(TaskFinder $taskFinder)
	{
		$this->taskFinder = $taskFinder;
	}

	/**
	 * @param GetTaskById $query
	 * @param Deferred|null $deferred
	 * @return array|null
	 */
	public function __invoke(GetTaskById $query, Deferred $deferred = null)
	{
		$response = $this->taskFinder->find($query->userId(), $query->taskId());

		if (null === $deferred) {
			return $response;
		}

		$deferred->resolve($response);
	}
}