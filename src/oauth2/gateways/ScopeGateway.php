<?php

namespace oauth2\gateways;

use gateways\AbstractGateway;
use gateways\GatewayInterface;

class ScopeGateway extends AbstractGateway implements GatewayInterface
{
	/**
	 * @inheritdoc
	 */
	protected function getTable()
	{
		return 'oauth2_scopes';
	}
}