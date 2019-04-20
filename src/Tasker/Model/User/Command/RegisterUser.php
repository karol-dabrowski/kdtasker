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
		Assertion::keyExists($payload, 'user_id', 'user_id|is_required');
		Assertion::uuid($payload['user_id'], 'user_id|must_be_correct_uuid');
		Assertion::keyExists($payload, 'email', 'email|is_required');
		Assertion::email($payload['email'], 'email|must_be_correct_email');
		Assertion::keyExists($payload, 'first_name', 'first_name|is_required');
		Assertion::string($payload['first_name'], 'first_name|must_be_a_string');
		Assertion::minLength($payload['first_name'], 3, 'first_name|must_be_between_3_and_80_characters');
		Assertion::maxLength($payload['first_name'], 80, 'first_name|must_be_between_3_and_80_characters');
		Assertion::keyExists($payload, 'last_name', 'last_name|is_required');
		Assertion::string($payload['last_name'], 'last_name|must_be_a_string');
		Assertion::minLength($payload['last_name'], 3, 'last_name|must_be_between_3_and_120_characters');
		Assertion::maxLength($payload['last_name'], 120, 'last_name|must_be_between_3_and_120_characters');
		Assertion::keyExists($payload, 'password', 'password|is_required');
		Assertion::string($payload['password'], 'password|must_be_a_string');
		Assertion::minLength($payload['password'], 8, 'password|must_be_between_8_and_30_characters');
		Assertion::maxLength($payload['password'], 30, 'password|must_be_between_8_and_30_characters');
		$this->payload = $payload;
	}
}