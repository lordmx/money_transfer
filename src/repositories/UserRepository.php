<?php

namespace repositories;

use entities\User;

/**
 * Репозиторий сущностей для сущности пользователя (сущность User)
 *
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
class UserRepository extends AbstractRepository implements RepositoryInterface
{
	/**
	 * @inheritdoc
	 */
	protected function createEntity()
	{
		return new User();
	}

	/**
	 * Найти пользователя по токену доступа
	 *
	 * @param string $accessToken
	 * @return User|null
	 */
	public function findByAccessToken($accessToken)
	{
		$criteria = ['accessToken' => $accessToken];
		$rows = $this->gateway->findByCriteria($criteria);

		if (!$rows) {
			return null;
		}

		return $this->populateEntity($rows[0]);
	}
}