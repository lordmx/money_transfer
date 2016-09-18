<?php

namespace gateways;

class UserGateway extends AbstractGateway implements GatewayInterface
{
	/**
	 * @inheritdoc
	 */
	protected function getTable()
	{
		return 'users';
	}
}