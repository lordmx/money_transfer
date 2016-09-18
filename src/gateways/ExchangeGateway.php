<?php

namespace gateways;

class ExchangeGateway extends AbstractGateway implements GatewayInterface
{
	/**
	 * @inheritdoc
	 */
	protected function getTable()
	{
		return 'exchange';
	}
}