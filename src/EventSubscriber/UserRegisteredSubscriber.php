<?php
declare(strict_types = 1);

namespace App\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use MongoDB\Database;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Tasker\Infrastructure\Projection\Table;
use Tasker\Model\User\Domain\User;
use Tasker\Model\User\Domain\UserId;
use Tasker\Model\User\Domain\UserName;

/**
 * Class UserRegisteredSubscriber
 * @package App\EventSubscriber
 */
final class UserRegisteredSubscriber implements EventSubscriber
{
	/**
	 * @var RequestStack
	 */
	private $requestStack;

	/**
	 * @var Database
	 */
	private $mongoConnection;

	/**
	 * UserRegisteredSubscriber constructor.
	 * @param RequestStack $requestStack
	 * @param Database $mongoConnection
	 */
	public function __construct(RequestStack $requestStack, Database $mongoConnection)
	{
		$this->requestStack = $requestStack;
		$this->mongoConnection = $mongoConnection;
	}

	/**
	 * @return array
	 */
	public function getSubscribedEvents(): array
	{
		return [Events::prePersist];
	}

	/**
	 * @param LifecycleEventArgs $args
	 * @throws \Exception
	 */
	public function prePersist(LifecycleEventArgs $args): void
	{
		$method = $this->requestStack->getCurrentRequest()->getMethod();
		$entity = $args->getObject();

		if(!$entity instanceof User || $method !== Request::METHOD_POST) {
			return;
		}

		$createdDateTime = new \DateTimeImmutable();
		$entity->setCreatedDateTime($createdDateTime);

		$modifiedDateTime = new \DateTime();
		$entity->setModifiedDateTime($modifiedDateTime);

		$this->insertInitialUserDisplayName($entity->id(), $entity->name());
	}

	/**
	 * @param UserId $userId
	 * @param UserName $userName
	 */
	private function insertInitialUserDisplayName(UserId $userId, UserName $userName): void
	{
		$user = [
			'user_id' => $userId->toString(),
			'display_name' => $userName->toString()
		];

		$collection = $this->mongoConnection->selectCollection(Table::MONGO_USERS_DISPLAY_NAMES);
		$collection->insertOne($user);
	}
}