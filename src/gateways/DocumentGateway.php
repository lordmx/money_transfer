<?php

namespace gateways;

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