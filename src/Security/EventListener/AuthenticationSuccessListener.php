<?php
declare(strict_types=1);

namespace App\Security\EventListener;

use App\Security\JWTUserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class AuthenticationSuccessListener
 * @package App\Security\EventListener
 */
class AuthenticationSuccessListener
{
	/**
	 * @var RequestStack
	 */
	private $requestStack;

	/**
	 * @var int
	 */
	private $tokenTtl;

	/**
	 * AuthenticationSuccessListener constructor.
	 * @param RequestStack $requestStack
	 * @param int $tokenTtl
	 */
	public function __construct(RequestStack $requestStack, int $tokenTtl)
	{
		$this->requestStack = $requestStack;
		$this->tokenTtl = $tokenTtl;
	}

	/**
	 * @param AuthenticationSuccessEvent $event
	 */
	public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
	{
		$user = $event->getUser();
		if (!$user instanceof JWTUserInterface) {
			throw new \InvalidArgumentException('internal_authorization_error');
		}

		$expirationTime = $this->tokenTtl + time();
		$data = $event->getData();
		$data['user_id'] = $user->getUserId()->toString();
		$data['token_expiration_time'] = $expirationTime;
		$event->setData($data);
	}
}