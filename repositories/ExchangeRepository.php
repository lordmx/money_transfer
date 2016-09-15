<?php

namespace repositories;

use entities\Exchange;

class ExchangeRepository extends AbstractRepository implements RepositoryInterface
{
	/**
	 * @inheritdoc
	 */
	protected function createEntity()
	{
		return new Exchange();
	}
}