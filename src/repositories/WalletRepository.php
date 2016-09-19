<?php

namespace repositories;

use entities\Wallet;

/**
 * Репозиторий сущностей для сущности кошелька пользователя (сущность Wallet)
 *
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
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