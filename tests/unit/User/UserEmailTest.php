<?php
declare(strict_types=1);

namespace App\Tests\Unit\User;

use Faker\Factory;
use Faker\Generator;
use Tasker\Model\User\Domain\UserEmail;

/**
 * Class UserEmailTest
 * @package App\Tests\Unit\User
 */
class UserEmailTest extends \Codeception\Test\Unit
{
	/**
	 * @var Generator
	 */
	private $faker;

	protected function _before()
	{
		$this->faker = Factory::create();
	}

	public function testUserEmailCanBeCreatedFromString()
	{
		$emailString = $this->faker->email;
		$userEmail = UserEmail::fromString($emailString);

		$this->assertInstanceOf(UserEmail::class, $userEmail);
		$this->assertSame($emailString, $userEmail->toString());
	}

	public function testUserEmailThrowsAnExceptionWhenCreatingFromIncorrectString()
	{
		$incorrectEmail = 'IncorrectEmail';
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('email|must_be_correct_email');
		UserEmail::fromString($incorrectEmail);

	}
}