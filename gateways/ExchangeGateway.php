<?php

namespace gateways;

class ExchangeGateway extends AbstractGateway implements GatewayInterface
{
	/**
	 * @inheritdoc
	 */
	public function getTable()
	{
		return 'exchange';
	}
}