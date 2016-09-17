<?php

namespace api\handlers;

use entities\User;
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use dto\HistoryDto;

class UserHistoryHandler extends AbstractHandler implements HandlerInterface
{
	/**
	 * @inheritdoc
	 */
	public function getCallback(User $user, Request $request)
	{
		$di = $this->di;

		return function() use ($user, $request, $di) {
			$walletId = $request->request->get('wallet_id');
			$dateFrom = $request->request->get('date_from');
			$dateTo = $request->request->get('date_to');
			$limit = (int)$request->request->get('limit', 10);
			$offset = (int)$request->request->get('offset', 0);
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
			$count = $dt->get('transactionRepository')->getHistoryCount($dto);

			return (new Result(new Metadata($limit, $offset, $count), 'transactions', $transactions))->toJson();
		};
	}
}