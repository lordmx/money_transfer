<?php

namespace oauth2\repositories;

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