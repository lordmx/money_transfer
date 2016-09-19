<?php

namespace oauth2\grants;

use oauth2\entities\Session;

/**
 * Интерфейс для способа авторизации пользователя через oauth2
 *
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
interface GrantInterface
{
    /**
     * Авторизовать пользователя и создать сессию через токен доступа
     *
     * @param string $token
     * @return Session
     */
    public function createSession($token);
}