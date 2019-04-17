<?php
declare(strict_types=1);

namespace App\Security\EventListener;

use App\Security\JWTUserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class JWTCreatedListener
 * @package App\Security\EventListener
 */
class JWTCreatedListener
{
	/**
	 * @var RequestStack
	 */
	private $requestStack;

	/**
	 * JWTCreatedListener constructor.
	 * @param RequestStack $requestStack
	 */
	public function __construct(RequestStack $requestStack)
	{
		$this->requestStack = $requestStack;
	}

	/**
	 * @param JWTCreatedEvent $event
	 */
	public function onJWTCreated(JWTCreatedEvent $event): void
	{
		$user = $event->getUser();
		if (!$user instanceof JWTUserInterface) {
			throw new \InvalidArgumentException('internal_authorization_error');
		}

		$payload = $event->getData();
		$payload['user_id'] = $user->getUserId()->toString();
		unset($payload['roles']);

		$event->setData($payload);
	}
}