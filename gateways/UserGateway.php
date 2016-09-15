<?php

namespace gateways;

class UserGateway extends AbstractGateway
{
	/**
	 * @inheritdoc
	 */
	public function getTable()
	{
		return 'users';
	}
}