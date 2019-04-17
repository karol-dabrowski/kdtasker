<?php
declare(strict_types = 1);

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Tasker\Model\User\Domain\User;
use Tasker\Model\User\Domain\UserEmail;
use Tasker\Model\User\Domain\UserId;
use Tasker\Model\User\Domain\UserRepository as UserRepositoryInterface;

/**
 * Class UserRepository
 * @package App\Repository
 */
class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
	/**
	 * UserRepository constructor.
	 * @param ManagerRegistry $registry
	 */
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, User::class);
	}

	/**
	 * @param User $user
	 * @throws \Doctrine\ORM\ORMException
	 * @throws \Doctrine\ORM\OptimisticLockException
	 */
	public function save(User $user): void
	{
		$this->_em->persist($user);
		$this->_em->flush();
	}

	/**
	 * @param UserId $userId
	 * @return User|null
	 */
	public function get(UserId $userId): ?User
	{
		return $this->find($userId);
	}

	/**
	 * @param UserEmail $email
	 * @return User|null
	 */
	public function getByEmail(UserEmail $email): ?User
	{
		return $this->findOneBy(['email.email' => $email->toString()]);
	}
}