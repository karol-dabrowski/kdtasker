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
		$options = [
			'typeMap' => [
				'root' => 'array',
				'document' => 'array',
			]
		];

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

		$tasks = $collection->aggregate($aggregate, $options)->toArray();
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

		$options = [
			'typeMap' => [
				'root' => 'array',
				'document' => 'array',
			]
		];

		$user = $collection->findOne($filter, $options);
		return $user ? $user['tasks'] : null;
	}
}