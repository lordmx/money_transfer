<?php

namespace api\handlers;

use entities\User;
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserBalanceHandler extends AbstractHandler implements HandlerInterface
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
			$count = 0;
			$limit = (int)$request->request->get('limit', 10);
			$offset = (int)$request->request->get('offset', 0);

			if (!$walletId) {
				$wallets = $di->get('walletRepository')->findAll($limit, $offset);
				$count = $di->get('walletRepository')->countAll();
			} else {
				$wallet = $di->get('walletRepository')->findById($walletId);

				if (!$wallet) {
					throw new NotFoundHttpException();
				}

				$wallets[] = $wallet;
				$count = 1;
			}

			$result = [];

			foreach ($wallets as $wallet) {
				$balance = $di->get('balanceService')->getBalanceFor($user, $wallet);
				$result[] = [
					'wallet_id' => $wallet->getId(),
					'balance' => $balance,
					'currency_id' => $wallet->getCurrencyId(),
				];
			}

			return (new Result(new Metadata($limit, $offset, $count), 'balance', $result))->toJson();
		};
	}
}