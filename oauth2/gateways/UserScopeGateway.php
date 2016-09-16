<?php

namespace oauth2\gateways;

use gateways\AbstractGateway;
use gateways\GatewayInterface;

class UserScopeGateway extends AbstractGateway implements GatewayInterface
{
	/**
	 * @inheritdoc
	 */
	public function getTable()
	{
		return 'oauth2_user_scopes';
	}

	/**
	 * @param int $userId
	 * @return int[]
	 */
	public function findUserScopeIds($userId)
	{
		return $this->conn->fetchColumn('SELECT scope_id FROM user_scopes WHERE user_id = ' . (int)$userId);
	}
}