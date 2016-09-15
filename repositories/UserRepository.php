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
}