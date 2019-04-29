<?php
declare(strict_types=1);

namespace App\Tests\Unit\User;

use Tasker\Model\User\Domain\UserPassword;

/**
 * Class UserPasswordTest
 * @package App\Tests\Unit\User
 */
class UserPasswordTest extends \Codeception\Test\Unit
{
	public function testUserPasswordCanBeCreatedFromString()
	{
		$passwordString = 'TestPassword1230';
		$userPassword = UserPassword::fromString($passwordString);
		$this->assertIsString($userPassword->toString());
		$this->assertNotSame($passwordString, $userPassword->toString());
	}

	public function testUserPasswordThrowsAnExceptionWhenCreatingFromIncorrectString()
	{
		$invalidPassword = 'TestPasswordWithoutDigits';
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('password|must_contain_at_least_one_letter_and_number');
		UserPassword::fromString($invalidPassword);
	}
}