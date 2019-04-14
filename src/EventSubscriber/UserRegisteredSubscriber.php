<?php
declare(strict_types = 1);

namespace App\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Tasker\Model\User\Domain\User;

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
	 * UserRegisteredSubscriber constructor.
	 * @param RequestStack $requestStack
	 */
	public function __construct(RequestStack $requestStack)
	{
		$this->requestStack = $requestStack;
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
	}
}