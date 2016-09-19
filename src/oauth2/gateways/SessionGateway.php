<?php

namespace oauth2\gateways;

use gateways\AbstractGateway;
use gateways\GatewayInterface;

/**
 * Шлюз для таблицы сессий пользователей API (oauth2_sessions)
 *
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
class SessionGateway extends AbstractGateway implements GatewayInterface
{
    /**
     * @inheritdoc
     */
    protected function getTable()
    {
        return 'oauth2_sessions';
    }
}