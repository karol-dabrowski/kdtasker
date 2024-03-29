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
	 * @var \DateTimeImmutable|null
	 */
	private $created;

	/**
	 * @var \DateTime|null
	 */
	private $modified;

	/**
	 * @var UserPassword
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
	 * @param UserPassword $password
	 * @return User
	 */
	public static function create(UserId $userId, UserEmail $userEmail, UserName $userName, UserPassword $password): User
	{
		$self = new self($userId, $userEmail, $userName, $password, false);

		return $self;
	}

	/**
	 * User constructor.
	 * @param UserId $id
	 * @param UserEmail $email
	 * @param UserName $username
	 * @param UserPassword $password
	 * @param bool $confirmed
	 */
	private function __construct(UserId $id, UserEmail $email, UserName $username, UserPassword $password, bool $confirmed)
	{
		$this->id = $id;
		$this->email = $email;
		$this->username = $username;
		$this->password = $password;
		$this->confirmed = $confirmed;
		$this->created = null;
		$this->modified = null;
	}

	/**
	 * @param \DateTimeImmutable $dateTime
	 */
	public function setCreatedDateTime(\DateTimeImmutable $dateTime): void
	{
		if($this->created !== null) {
			throw new \LogicException('creation_date_cannot_be_changed');
		}

		$this->created = $dateTime;
	}

	/**
	 * @param \DateTime $dateTime
	 */
	public function setModifiedDateTime(\DateTime $dateTime): void
	{
		$this->modified = $dateTime;
	}

	/**
	 * @return UserPassword
	 */
	public function password(): UserPassword
	{
		return $this->password;
	}

	/**
	 * @return UserEmail
	 */
	public function email(): UserEmail
	{
		return $this->email;
	}

	/**
	 * @return UserId
	 */
	public function id(): UserId
	{
		return $this->id;
	}

	/**
	 * @return UserName
	 */
	public function name(): UserName
	{
		return $this->username;
	}
}