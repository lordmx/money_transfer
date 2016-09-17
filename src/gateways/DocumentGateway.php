<?php

namespace gateways;

class DocumentGateway extends AbstractGateway implements GatewayInterface
{
	/**
	 * @inheritdoc
	 */
	public function getTable()
	{
		return 'documents';
	}
}