<?php
declare(strict_types=1);

namespace Tasker\Infrastructure\Projection;

final class Table
{
	const USERS = 'users';
	const MONGO_USERS_DISPLAY_NAMES = 'users_display_names';
	const READ_MONGO_TASKS = 'tasks_collection';
}