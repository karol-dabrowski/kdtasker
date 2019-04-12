<?php
declare(strict_types=1);

namespace Tasker\Model\User\Domain;

/**
 * Class User
 * @package Tasker\Model\User\Domain
 */
class User
{
	/**
	 * @var UserId
	 */
	private $id;

	/**
	 * @var UserEmail
	 */
	private $email;

	/**
	 * @var UserName
	 */
	private $username;

	/**
	 * @var string
	 */
	private $password;

	/**
	 * @var bool
	 */
	private $confirmed;

	/**
	 * @param UserId $userId
	 * @param UserEmail $userEmail
	 * @param UserName $userName
	 * @param string $password
	 * @return User
	 */
	public static function create(UserId $userId, UserEmail $userEmail, UserName $userName, string $password): User
	{
		$self = new self($userId, $userEmail, $userName, $password, false);

		return $self;
	}

	/**
	 * User constructor.
	 * @param UserId $id
	 * @param UserEmail $email
	 * @param UserName $username
	 * @param string $password
	 * @param bool $confirmed
	 */
	private function __construct(UserId $id, UserEmail $email, UserName $username, string $password, bool $confirmed)
	{
		$this->id = $id;
		$this->email = $email;
		$this->username = $username;
		$this->password = $password;
		$this->confirmed = $confirmed;
	}
}