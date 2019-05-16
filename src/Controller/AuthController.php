<?php
declare(strict_types = 1);

namespace App\Controller;

use App\Util\Response\ErrorResponseFactory;
use Prooph\Common\Messaging\MessageFactory;
use Prooph\ServiceBus\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AuthController
 * @package App\Controller
 */
final class AuthController
{
	/**
	 * @var CommandBus
	 */
	private $commandBus;

	/**
	 * @var MessageFactory
	 */
	private $messageFactory;

	/**
	 * AuthController constructor.
	 * @param CommandBus $commandBus
	 * @param MessageFactory $messageFactory
	 */
	public function __construct(CommandBus $commandBus, MessageFactory $messageFactory)
	{
		$this->commandBus = $commandBus;
		$this->messageFactory = $messageFactory;
	}

	/**
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function register(Request $request)
	{
		$data = json_decode($request->getContent(), true);
		$payload = ['payload' => $data['payload']];
		$commandName = 'Tasker\Model\User\Command\RegisterUser';

		try {
			$command = $this->messageFactory->createMessageFromArray($commandName, $payload);
			$this->commandBus->dispatch($command);
		} catch(\Exception $exception) {
			return ErrorResponseFactory::createResponse($exception);
		}

		return new JsonResponse();
	}
}
