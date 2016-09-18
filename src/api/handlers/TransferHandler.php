<?php

namespace api\handlers;

use api\Result;
use api\Metadata;
use entities\User;
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use dto\TransferDto;

class TransferHandler extends AbstractHandler implements HandlerInterface
{
	/**
	 * @inheritdoc
	 */
	public function getRoute()
	{
		return '/users/transactions';
	}

	/**
	 * @inheritdoc
	 */
	public function getMethod()
	{
		return \api\Server::METHOD_POST;
	}

	/**
	 * @inheritdoc
	 */
	public function getCallback(Request $request)
	{
		$handler = $this;

		return function() use ($handler, $request) {
			$di = $handler->getContainer();
			$user = $handler->getUser();

			$handler->ensureUserPermitted($user);
			$data = json_decode($request->getContent(), true);

			if (json_last_error()) {
				throw new BadRequestHttpException('Wrong data format');
			}

			$dto = TransferDto::fromMap($data);
			$document = $di->get('transferService')->transfer($user, $dto);

			if ($document->isError()) {
				throw new BadRequestHttpException($document->getError());
			}

			$transactions = $di->get('transactionRepository')->findByDocument($document);
			$count = count($transactions);

			return (new Result(new Metadata(0, 0, $count), 'transactions', $transactions))->toJson();
		};
	}
}