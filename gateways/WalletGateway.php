<?php

namespace gateways;

class WalletGateway extends AbstractGateway implements GatewayInterface
{
	/**
	 * @inheritdoc
	 */
	public function getTable()
	{
		return 'wallets';
	}

	/**
	 * @param int $userId
	 * @param int $walletId
	 * @return float
	 */
	public function getBalance($userId, $walletId)
	{
		$row = $this->conn->fetchArray(
			'SELECT SUM(amount) FROM ' . $this->getTable() . ' WHERE user_id = ' . $userId . ' AND wallet_id = ' . $walletId
		)

		if (!$row) {
			return 0.0;
		}

		return (float)reset($row);
	}
}