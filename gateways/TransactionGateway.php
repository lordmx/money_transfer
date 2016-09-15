<?php

namespace gateways;

class TransactionGateway extends AbstractGateway implements GatewayInterface
{
	/**
	 * @inheritdoc
	 */
	public function getTable()
	{
		return 'transactions';
	}
}