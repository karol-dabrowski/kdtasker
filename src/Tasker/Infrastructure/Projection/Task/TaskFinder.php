<?php
declare( strict_types=1 );

namespace Tasker\Infrastructure\Projection\Task;

use MongoDB\Database;
use Tasker\Infrastructure\Projection\Table;
use Tasker\Model\Task\Domain\TaskId;
use Tasker\Model\User\Domain\UserId;

/**
 * Class TaskFinder
 * @package Tasker\Infrastructure\Projection\Task
 */
class TaskFinder
{
	/**
	 * @var Database
	 */
	private $mongoConnection;

	/**
	 * TaskFinder constructor.
	 * @param Database $mongoConnection
	 */
	public function __construct(Database $mongoConnection)
	{
		$this->mongoConnection = $mongoConnection;
	}

	/**
	 * @param TaskId $id
	 */
	public function findById(TaskId $id)
	{
		//@TODO Implement
	}

	/**
	 * @param UserId $id
	 */
	public function findUserTasks(UserId $id)
	{
		//@TODO Implement
	}

	/**
	 * @param UserId $id
	 * @param string $day
	 * @return array
	 */
	public function findExtendedUserTasksForSpecifiedDay(UserId $id, string $day)
	{
		$collection = $this->mongoConnection->selectCollection(Table::READ_MONGO_OPEN_TASKS);

		$aggregate = [
			[
				'$match' => [
					'user_id' => $id->toString()
				]
			],
			[
				'$unwind' => '$days'
			],
			[
				'$replaceRoot' => [
					'newRoot' => '$days'
				]
			],
			[
				'$match' => [
					'deadline_date' => $day
				]
			]
		];

		$tasks = $collection->aggregate($aggregate, $this->getOptions())->toArray();
		$response = $this->sortTasks($tasks);

		return $response;
	}

	/**
	 * @return array
	 */
	private function getOptions(): array
	{
		return [
			'typeMap' => [
				'root' => 'array',
				'document' => 'array',
			]
		];
	}

	/**
	 * @param array $tasks
	 * @return array
	 */
	private function sortTasks(array $tasks): array
	{
		$tasksWithTime = [];

		foreach ($tasks as $task) {
			if(!is_null($task['deadline_time'])) {
				array_push($tasksWithTime, $task);
				array_pop($tasks);
			}
		}

		return array_merge($tasksWithTime, $tasks);
	}
}