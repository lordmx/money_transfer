<?php

namespace repositories;

use entities\User;

class UserRepository extends AbstractRepository implements Repository
{
	/**
	 * @inheritdoc
	 */
	protected function createEntity()
	{
		return new User();
	}
}