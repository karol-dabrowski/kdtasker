<?php
declare(strict_types=1);

namespace Tasker\Model\User\Domain;

/**
 * Class UserEmail
 * @package Tasker\Model\User\Domain
 */
class UserEmail
{
	/**
	 * @var string
	 */
	private $email;

	/**
	 * @param string $email
	 * @return UserEmail
	 */
	public static function fromString(string $email): UserEmail
	{
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			throw new \InvalidArgumentException('invalid_email');
		}

		return new self($email);
	}

	/**
	 * UserEmail constructor.
	 * @param string $email
	 */
	private function __construct(string $email)
	{
		$this->email = $email;
	}

	/**
	 * @return string
	 */
	public function toString(): string
	{
		return $this->email;
	}
}