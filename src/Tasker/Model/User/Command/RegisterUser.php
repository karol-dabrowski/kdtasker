<?php
declare(strict_types=1);

namespace Tasker\Model\User\Command;

use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadConstructable;
use Prooph\Common\Messaging\PayloadTrait;
use Tasker\Model\User\Domain\UserEmail;
use Tasker\Model\User\Domain\UserId;
use Assert\Assertion;
use Tasker\Model\User\Domain\UserName;

/**
 * Class RegisterUser
 * @package Tasker\Model\User\Command
 */
class RegisterUser extends Command implements PayloadConstructable
{
	use PayloadTrait;

	/**
	 * @return UserId
	 */
	public function userId(): UserId
	{
		return UserId::fromString($this->payload['user_id']);
	}

	/**
	 * @return UserEmail
	 */
	public function email(): UserEmail
	{
		return UserEmail::fromString($this->payload['email']);
	}

	/**
	 * @return UserName
	 */
	public function name(): UserName
	{
		return UserName::fromString($this->payload['first_name'], $this->payload['last_name']);
	}

	/**
	 * @return string
	 */
	public function password(): string
	{
		return $this->payload['password'];
	}

	/**
	 * @param array $payload
	 */
	protected function setPayload(array $payload): void
	{
		Assertion::keyExists($payload, 'user_id');
		Assertion::uuid($payload['user_id']);
		Assertion::keyExists($payload, 'email');
		Assertion::email($payload['email']);
		Assertion::keyExists($payload, 'first_name');
		Assertion::string($payload['first_name']);
		Assertion::keyExists($payload, 'last_name');
		Assertion::string($payload['last_name']);
		Assertion::keyExists($payload, 'password');
		Assertion::string($payload['password']);
		$this->payload = $payload;
	}
}