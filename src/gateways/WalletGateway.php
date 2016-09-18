<?php

namespace gateways;

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