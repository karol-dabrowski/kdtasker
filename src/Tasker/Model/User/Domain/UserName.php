<?php
declare(strict_types=1);

namespace Tasker\Model\User\Domain;

/**
 * Class UserName
 * @package Tasker\Model\User\Domain
 */
class UserName
{
	/**
	 * @var string
	 */
	private $firstName;

	/**
	 * @var string
	 */
	private $lastName;

	/**
	 * @param string $firstName
	 * @param string $lastName
	 * @return UserName
	 */
	public static function fromString(string $firstName, string $lastName): UserName
	{
		$firstNameRegexp = '/^[a-zA-Z]{3,80}$/';
		$lastNameRegexp = '/^[a-zA-Z-]{3,120}$/';

		if(!filter_var(
			$firstName,
			FILTER_VALIDATE_REGEXP,
			['options' => ['regexp' => $firstNameRegexp]])
		) {
			throw new \InvalidArgumentException('invalid_first_name');
		}

		if(!filter_var(
			$lastName,
			FILTER_VALIDATE_REGEXP,
			['options' => ['regexp' => $lastNameRegexp]])
		) {
			throw new \InvalidArgumentException('invalid_last_name');
		}

		return new self($firstName, $lastName);
	}

	/**
	 * UserName constructor.
	 * @param string $firstName
	 * @param string $lastName
	 */
	private function __construct(string $firstName, string $lastName)
	{
		$this->firstName = $firstName;
		$this->lastName = $lastName;
	}

	/**
	 * @return string
	 */
	public function toString(): string
	{
		return $this->firstName . ' ' . $this->lastName;
	}
}