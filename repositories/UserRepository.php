<?php

namespace repositories;

use entities\User;

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