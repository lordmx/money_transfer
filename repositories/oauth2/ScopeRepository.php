<?php

namespace repositories\oauth2;

use entities\oauth2\Scope;
use repositories\AbstractRepository;
use repositories\RepositoryInterface;

class ScopeRepository extends AbstractRepository implements RepositoryInterface
{
	/**
	 * @inheritdoc
	 */
	protected function createEntity()
	{
		return new Scope();
	}
}