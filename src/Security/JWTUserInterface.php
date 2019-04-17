<?php
declare(strict_types=1);

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Tasker\Model\User\Domain\UserId;

/**
 * Interface JWTUserInterface
 * @package App\Security
 */
interface JWTUserInterface extends UserInterface
{
	/**
	 * @return UserId
	 */
	public function getUserId(): UserId;
}