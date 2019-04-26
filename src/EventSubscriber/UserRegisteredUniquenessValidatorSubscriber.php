<?php
declare(strict_types = 1);

namespace App\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Tasker\Model\User\Domain\User;

/**
 * Class UserRegisteredUniquenessValidatorSubscriber
 * @package App\EventSubscriber
 */
final class UserRegisteredUniquenessValidatorSubscriber implements EventSubscriber
{
	/**
	 * @var RequestStack
	 */
	private $requestStack;

	/**
	 * @var ValidatorInterface
	 */
	private $validator;

	/**
	 * UserRegisteredUniquenessValidatorSubscriber constructor.
	 * @param RequestStack $requestStack
	 * @param ValidatorInterface $validator
	 */
	public function __construct(RequestStack $requestStack, ValidatorInterface $validator)
	{
		$this->requestStack = $requestStack;
		$this->validator = $validator;
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
	 */
	public function prePersist(LifecycleEventArgs $args): void
	{
		$method = $this->requestStack->getCurrentRequest()->getMethod();
		$entity = $args->getObject();

		if(!$entity instanceof User || $method !== Request::METHOD_POST) {
			return;
		}

		$errors = $this->validator->validate($entity);
		if(count($errors) > 0) {
			throw new \InvalidArgumentException($errors[0]->getMessage());
		}
	}
}