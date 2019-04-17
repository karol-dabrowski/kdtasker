<?php
declare(strict_types=1);

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Tasker\Model\User\Domain\User;

/**
 * Class JwtUserWrapper
 * @package App\Security
 */
final class JwtUserWrapper implements UserInterface
{
	/**
	 * @var User
	 */
	private $user;

	/**
	 * JwtUserWrapper constructor.
	 * @param User $user
	 */
	private function __construct(User $user)
	{
		$this->user = $user;
	}

	/**
	 * @param User $user
	 * @return JwtUserWrapper
	 */
	public static function createUserWrapperFromUser(User $user)
	{
		return new self($user);
	}

	/**
	 * @return array
	 */
	public function getRoles(): array
	{
		return [];
	}

	/**
	 * @return string
	 */
	public function getSalt(): string
	{
		return '';
	}

	public function eraseCredentials(): void
	{

	}

	/**
	 * @return string
	 */
	public function getPassword()
	{
		return $this->user->getPassword();
	}

	/**
	 * @return string
	 */
	public function getUsername(): string
	{
		return $this->user->getEmail()->toString();
	}
}