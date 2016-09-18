<?php

namespace api\handlers;

use api\Result;
use api\Metadata;
use entities\User;
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserBalanceHandler extends AbstractHandler implements HandlerInterface
{
	/**
	 * @inheritdoc
	 */
	public function getRoute()
	{
		return '/users/balance';
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

			$count = 0;
			$walletId = $request->get('wallet_id');
			$limit = (int)$request->get('limit', 10);
			$offset = (int)$request->get('offset', 0);
			$wallets = [];

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