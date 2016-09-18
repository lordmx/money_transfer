<?php

namespace oauth2\gateways;

use gateways\AbstractGateway;
use gateways\GatewayInterface;

/**
 * Шлюз для таблицы связи пользователей и разрешений API (oauth2_user_scopes)
 *
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
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
	 * Получить идентификаторы разрешений пользователя
	 *
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