<?php
declare(strict_types=1);

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Tasker\Model\User\Domain\User;
use Tasker\Model\User\Domain\UserEmail;
use Tasker\Model\User\Domain\UserRepository;

/**
 * Class UserProvider
 * @package App\Security
 */
class UserProvider implements UserProviderInterface
{
	/**
	 * @var UserRepository
	 */
	private $users;

	/**
	 * UserProvider constructor.
	 * @param UserRepository $users
	 */
	public function __construct(UserRepository $users)
	{
		$this->users = $users;
	}

	/**
	 * @param string $username
	 * @return User|null
	 */
	public function loadUserByUsername($username): ?JwtUserWrapper
	{
		return $this->loadUserWrapperByEmail($username);
	}

	/**
	 * @param UserInterface $user
	 */
	public function refreshUser(UserInterface $user): void
	{
		return;
	}

	/**
	 * @param string $class
	 * @return bool
	 */
	public function supportsClass($class): bool
	{
		return $class === User::class;
	}

	/**
	 * @param string $userEmail
	 * @return JwtUserWrapper|null
	 */
	private function loadUserWrapperByEmail(string $userEmail): ?JwtUserWrapper
	{
		$user = $this->users->getByEmail(UserEmail::fromString($userEmail));
		return $user ? JwtUserWrapper::createUserWrapperFromUser($user) : null;
	}
}