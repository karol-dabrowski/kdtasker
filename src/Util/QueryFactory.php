<?php
declare(strict_types = 1);

namespace App\Util;

use Tasker\Model\Task\Query\GetTaskById;
use Tasker\Model\Task\Query\GetTasksByUserId;
use Tasker\Model\Task\Query\GetUserOpenTasksForNextDays;
use Tasker\Model\Task\Query\GetUserTodaysTasks;

/**
 * Class QueryFactory
 * @package App\Util
 */
class QueryFactory
{
	/**
	 * @param string $queryName
	 * @param array $attributes
	 * @param array $parameters
	 *
	 * @return GetTaskById|GetTasksByUserId|GetUserOpenTasksForNextDays|GetUserTodaysTasks|null
	 */
	public static function createQuery(string $queryName, array $attributes, array $parameters)
	{
		switch (true) {
			case GetTaskById::class === $queryName:
				return new GetTaskById($attributes['task_id'], $attributes['authenticated_user_id']);
			case GetTasksByUserId::class === $queryName:
				return new GetTasksByUserId($attributes['user_id']);
			case GetUserTodaysTasks::class === $queryName:
				return new GetUserTodaysTasks($attributes['authenticated_user_id']);
			case GetUserOpenTasksForNextDays::class === $queryName:
				$days = isset($parameters['days']) ? intval($parameters['days']) : null;
				return !is_null($days) && $days > 0 && $days <= 30
					? new GetUserOpenTasksForNextDays($attributes['authenticated_user_id'], $days)
					: new GetUserOpenTasksForNextDays($attributes['authenticated_user_id']);
			default:
				return null;
		}
	}
}