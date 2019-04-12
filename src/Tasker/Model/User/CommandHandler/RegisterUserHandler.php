<?php
declare(strict_types=1);

namespace Tasker\Model\User\CommandHandler;

use Tasker\Model\User\Command\RegisterUser;
use Tasker\Model\User\Domain\User;
use Tasker\Model\User\Domain\UserRepository;

/**
 * Class RegisterUserHandler
 * @package Tasker\Model\User\CommandHandler
 */
class RegisterUserHandler
{
	/**
	 * @var UserRepository
	 */
	private $users;

	/**
	 * RegisterUserHandler constructor.
	 * @param UserRepository $userRepository
	 */
	public function __construct(UserRepository $userRepository)
	{
		$this->users = $userRepository;
	}

	/**
	 * @param RegisterUser $command
	 */
	public function __invoke(RegisterUser $command): void
	{
		$userId = $command->userId();
		$userEmail = $command->email();
		$userName = $command->name();
		$password = $command->password();

		$user = User::create($userId, $userEmail, $userName, $password);
		$this->users->save($user);
	}

}