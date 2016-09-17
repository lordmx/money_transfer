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
}