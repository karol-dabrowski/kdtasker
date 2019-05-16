<?php
declare(strict_types = 1);

namespace App\Controller;

use App\Util\Response\ErrorResponseFactory;
use Prooph\Common\Messaging\MessageFactory;
use Prooph\ServiceBus\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ApiController
 * @package App\Controller
 */
final class ApiController
{
	/**
	 * @var CommandBus
	 */
	private $commandBus;

	/**
	 * @var MessageFactory
	 */
	private $messageFactory;

	private const NAME_ATTRIBUTE = 'command';

	/**
	 * ApiController constructor.
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
	public function postAction(Request $request)
	{
		$data = json_decode($request->getContent(), true);
		$commandName = $request->attributes->get(self::NAME_ATTRIBUTE);
		$payload = ['payload' => $data['payload']];

		try {
			$command = $this->messageFactory->createMessageFromArray($commandName, $payload);
			$this->commandBus->dispatch($command);
		} catch(\Exception $exception) {
			return ErrorResponseFactory::createResponse($exception);
		}

		return new JsonResponse();
	}
}