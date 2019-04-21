<?php
declare(strict_types=1);

namespace Tasker\Model\User\Domain;

/**
 * Class UserPassword
 * @package Tasker\Model\User\Domain
 */
class UserPassword
{
	/**
	 * @var string
	 */
	private $hashedPassword;

	/**
	 * @param string $plainTextPassword
	 * @return UserPassword
	 */
	public static function fromString(string $plainTextPassword): UserPassword
	{
		$passwordRegexp = '/^(?=.*?[a-zA-Z])(?=.*?[0-9]).{8,30}$/';

		if(!filter_var(
			$plainTextPassword,
			FILTER_VALIDATE_REGEXP,
			['options' => ['regexp' => $passwordRegexp]])
		) {
			throw new \InvalidArgumentException('password|must_contain_at_least_one_letter_and_number');
		}

		$hashedPassword = password_hash($plainTextPassword, PASSWORD_DEFAULT);
		return new self($hashedPassword);
	}

	/**
	 * UserPassword constructor.
	 * @param string $hashedPassword
	 */
	private function __construct(string $hashedPassword)
	{
		$this->hashedPassword = $hashedPassword;
	}

	/**
	 * @return string
	 */
	public function toString(): string
	{
		return $this->hashedPassword;
	}
}