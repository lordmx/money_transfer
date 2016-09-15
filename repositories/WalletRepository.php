<?php

namespace repositories;

use entities\Wallet;

class WalletRepository extends AbstractRepository implements RepositoryInterface
{
	/**
	 * @inheritdoc
	 */
	protected function createEntity()
	{
		return new Wallet();
	}
}