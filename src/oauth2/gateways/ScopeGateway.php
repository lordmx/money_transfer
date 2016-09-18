<?php

namespace oauth2\gateways;

use gateways\AbstractGateway;
use gateways\GatewayInterface;

/**
 * Шлюз для таблицы разрешений API (oauth2_scopes)
 *
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
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