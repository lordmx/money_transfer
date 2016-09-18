<?php

namespace api\handlers;

use api\Result;
use api\Metadata;
use entities\User;
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use dto\HistoryDto;

class UserHistoryHandler extends AbstractHandler implements HandlerInterface
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
	public function getCallback(Request $request)
	{
		$handler = $this;

		return function() use ($handler, $request) {
			$di = $handler->getContainer();
			$user  = $handler->getUser();

			$walletId = $request->get('wallet_id');
			$dateFrom = $request->get('date_from');
			$dateTo = $request->get('date_to');
			$limit = (int)$request->get('limit', 10);
			$offset = (int)$request->get('offset', 0);
			$result = [];

			$dto = new HistoryDto();
			$dto->setUserId($user->getId());

			if ($walletId) {
				$dto->setWalletId($walletId);
			}

			if ($dateFrom) {
				$dto->setDateFrom(new \DateTime($dateFrom));
			}

			if ($dateTo) {
				$dto->setDateTo(new \DateTime($dateTo));
			}

			$transactions = $di->get('transactionRepository')->findHistory($dto, $limit, $offset);
			$count = $di->get('transactionRepository')->getHistoryCount($dto);

			return (new Result(new Metadata($limit, $offset, $count), 'transactions', $transactions))->toJson();
		};
	}
}