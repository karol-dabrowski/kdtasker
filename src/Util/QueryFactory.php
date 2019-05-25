<?php
declare(strict_types = 1);

namespace App\Util;

use Tasker\Model\Task\Query\GetTaskById;

/**
 * Class QueryFactory
 * @package App\Util
 */
class QueryFactory
{
	/**
	 * @param string $queryName
	 * @param array $attributes
	 * @return GetTaskById|null
	 */
	public static function createQuery(string $queryName, array $attributes)
	{
		switch (true) {
			case GetTaskById::class === $queryName:
				return new GetTaskById($attributes['task_id']);
			default:
				return null;
		}
	}
}