<?php

namespace gateways;

/**
 * Шлюз для таблицы с курсами валют (exchange)
 *
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
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