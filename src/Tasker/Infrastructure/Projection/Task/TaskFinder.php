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
	 * @return array|null
	 */
	public function findById(TaskId $id)
	{
		$collection = $this->mongoConnection->selectCollection(Table::READ_MONGO_TASKS);
		$filters = ['task_id' => $id->toString()];

		$aggregate = [
			['$match' => $filters],
			['$limit' => 1],
			[
				'$lookup' => [
					'from' => Table::MONGO_USERS_DISPLAY_NAMES,
					'localField' => 'creator_id',
					'foreignField' => 'user_id',
					'as' => 'creatorArray'
				],
			],
			[
				'$project' => [
					'_id' => 0,
					'task_id' => 1,
					'title' => 1,
					'creator' => [
						'id' => [
							'$arrayElemAt' => ['$creatorArray.user_id', 0]
						],
						'name' => [
							'$arrayElemAt' => ['$creatorArray.display_name', 0]
						]
					]
				]
			]
		];

		$tasks = $collection->aggregate($aggregate, $this->getOptions())->toArray();
		return $tasks ? $tasks[0] : null;
	}

	/**
	 * @param UserId $id
	 * @return array|null
	 */
	public function findUserTasks(UserId $id)
	{
		$collection = $this->mongoConnection->selectCollection(Table::READ_MONGO_USER_TASKS);

		$filter = [
			'user_id' => $id->toString()
		];

		$user = $collection->findOne($filter, $this->getOptions());
		return $user ? $user['tasks'] : null;
	}

	/**
	 * @param UserId $id
	 * @param string $day
	 * @return array
	 */
	public function findExtendedUserTasksForSpecifiedDay(UserId $id, string $day)
	{
		$collection = $this->mongoConnection->selectCollection(Table::READ_MONGO_USER_TASKS);

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
					'date' => $day
				]
			]
		];

		$response = $collection->aggregate($aggregate, $this->getOptions())->toArray();
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
}