<?php

namespace repositories\oauth2;

use oauth2\entities\Scope;
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