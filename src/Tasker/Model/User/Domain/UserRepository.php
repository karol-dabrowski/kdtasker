<?php
declare(strict_types=1);

namespace Tasker\Model\User\Domain;

/**
 * Interface UserRepository
 * @package Tasker\Model\User\Domain
 */
interface UserRepository
{
	/**
	 * @param User $user
	 */
	public function save(User $user): void;

	/**
	 * @param UserId $userId
	 * @return User|null
	 */
	public function get(UserId $userId): ?User;
}