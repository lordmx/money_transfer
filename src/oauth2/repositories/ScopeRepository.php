<?php

namespace oauth2\repositories;

use oauth2\entities\Scope;
use repositories\AbstractRepository;
use repositories\RepositoryInterface;

/**
 * Репозиторий сущностей для сущности разрешений API (сущность Scope)
 *
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
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