<?php
declare(strict_types=1);

namespace Tasker\Model\User\Domain;

use Tasker\Model\Id;
use Tasker\Model\IdTrait;

/**
 * Class UserId
 * @package Tasker\Model\User\Domain
 */
final class UserId implements Id
{
	use IdTrait;
}