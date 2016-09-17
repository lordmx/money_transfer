<?php

namespace api\handlers;

use entities\User;
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use dto\TransferDto;

class UserHistoryHandler extends AbstractHandler implements HandlerInterface
{
	/**
	 * @inheritdoc
	 */
	public function getRoute()
	{
		return '/users/transfer';
	}

	/**
	 * @inheritdoc
	 */
	public function getMethod()
	{
		return api\Server::METHOD_POST;
	}

	/**
	 * @inheritdoc
	 */
	public function getCallback(User $user, Request $request)
	{
		$di = $this->di;

		return function() use ($user, $request, $di) {
			$dto = TransferDto::fromMap($request->request->all());
			$document = $di->get('transferService')->transfer($user, $dto);

			if ($document->isError()) {
				throw new BadRequestHttpException($document->getError());
			}

			$transactions = $di->get('transactionRepository')->findByDocument($document);
			$count = count($transactions);

			return (new Result(new Metadata(1, 0, $count), 'transactions', $transactions))->toJson();
		};
	}
}