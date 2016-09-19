<?php

namespace gateways;

/**
 * Шлюз для таблицы пользователей (users)
 *
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
class UserGateway extends AbstractGateway implements GatewayInterface
{
    /**
     * @inheritdoc
     */
    protected function getTable()
    {
        return 'users';
    }
}