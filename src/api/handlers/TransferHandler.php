<?php

namespace api\handlers;

use api\Result;
use api\Metadata;
use entities\User;
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use dto\TransferDto;

/**
 * API-метод для совершения перевода от пользователя к пользователю (в качестве исходного пользователя будет взят текущий авторизованный).
 *
 * POST /api/v1/users/transfer
 *
 * {
 *   "source_wallet_id": 1,
 *   "target_wallet_id": 1,
 *   "target_user_id": 2,
 *   "amount": 10.5,
 *   "notice": "Тестовый перевод"	
 * }
 *
 * В качестве ответа будет получена пара транзакций (на списание и зачисления по кошелькам):
 *
 * {
 *   "resultset": {
 *	   "metadata": {
 *		 "limit": 0,
 *       "offset": 0,
 *       "count": 2
 *     } 	
 *   },
 *   "transactions": [
 *     {
 *		 "id": 1,
 *       "document_id": 1,
 *       "user_id": 1,
 *       "wallet_id": 1,
 *       "created_at": "2016-01-01T10:00:00",
 *       "amount": -10.5
 *     },
 *     {
 *		 "id": 2,
 *       "document_id": 1,
 *       "user_id": 2,
 *       "wallet_id": 1,
 *       "created_at": "2016-01-01T10:00:00",
 *       "amount": 10.5
 *     }
 *   ]	
 * }
 * 
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
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