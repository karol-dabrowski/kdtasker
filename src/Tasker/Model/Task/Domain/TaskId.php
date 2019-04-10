<?php
declare(strict_types=1);

namespace Tasker\Model\Task\Domain;

use Tasker\Model\Id;
use Tasker\Model\IdTrait;

/**
 * Class TaskId
 * @package Tasker\Model\Task\Domain
 */
final class TaskId implements Id
{
	use IdTrait;
}