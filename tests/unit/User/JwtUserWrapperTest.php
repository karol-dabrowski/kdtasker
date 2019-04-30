<?php
declare(strict_types=1);

namespace App\Tests\Unit\User;

use App\Security\JwtUserWrapper;
use Faker\Factory;
use Tasker\Model\User\Domain\User;
use Tasker\Model\User\Domain\UserEmail;
use Tasker\Model\User\Domain\UserId;
use Tasker\Model\User\Domain\UserName;
use Tasker\Model\User\Domain\UserPassword;

/**
 * Class JwtUserWrapperTest
 * @package App\Tests\Unit\User
 */
class JwtUserWrapperTest extends \Codeception\Test\Unit
{
	/**
	 * @var User
	 */
	private $user;

	protected function _before()
	{
		$faker = Factory::create();

		$this->user = User::create(
			UserId::generate(),
			UserEmail::fromString($faker->email),
			UserName::fromString($faker->firstName, $faker->lastName),
			UserPassword::fromString("UserPass123"),
			false
		);
	}

	public function testJwtUserWrapperCanBeCreatedWithUser()
	{
		$jwtUser = JwtUserWrapper::createUserWrapperFromUser($this->user);

		$this->assertInstanceOf(JwtUserWrapper::class, $jwtUser);
		$this->assertInstanceOf(UserId::class, $jwtUser->getUserId());
		$this->assertSame($this->user->id(), $jwtUser->getUserId());
		$this->assertIsString($jwtUser->getUsername());
		$this->assertSame($this->user->email()->toString(), $jwtUser->getUsername());
		$this->assertIsString($jwtUser->getPassword());
		$this->assertSame($this->user->password()->toString(), $jwtUser->getPassword());
	}
}