<?php

namespace gateways;

/**
 * Шлюз для таблицы кошельков (wallets)
 *
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
class WalletGateway extends AbstractGateway implements GatewayInterface
{
	/**
	 * @inheritdoc
	 */
	protected function getTable()
	{
		return 'wallets';
	}
}