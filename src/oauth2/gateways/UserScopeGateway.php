<?php

namespace oauth2\gateways;

use gateways\AbstractGateway;
use gateways\GatewayInterface;

class UserScopeGateway extends AbstractGateway implements GatewayInterface
{
	/**
	 * @inheritdoc
	 */
	protected function getTable()
	{
		return 'oauth2_user_scopes';
	}

	/**
	 * @param int $userId
	 * @return int[]
	 */
	public function findUserScopeIds($userId)
	{
		$rows = $this->findByCriteria(['userId' => (int)$userId]);
		$ids = [];

		foreach ($rows as $row) {
			$ids[] = $row['scopeId'];
		}

		return $ids;
	}
}