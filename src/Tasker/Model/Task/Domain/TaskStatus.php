<?php
declare(strict_types=1);

namespace Tasker\Model\Task\Domain;

/**
 * Class TaskStatus
 * @package Tasker\Model\Task\Domain
 */
final class TaskStatus
{
	public const OPEN = 1;
	public const COMPLETED = 2;
	public const DELETED = 3;
}