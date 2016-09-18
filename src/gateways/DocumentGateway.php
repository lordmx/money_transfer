<?php

namespace gateways;

/**
 * Шлюз для таблицы документов (documents)
 *
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
class DocumentGateway extends AbstractGateway implements GatewayInterface
{
	/**
	 * @inheritdoc
	 */
	protected function getTable()
	{
		return 'documents';
	}
}