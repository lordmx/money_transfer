<?php

namespace api\handlers;

use api\Metadata;
use entities\User;
use \Symfony\Component\HttpFoundation\Request;

/**
 * Интерфейс обработчика API-метода
 * 
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
interface HandlerInterface
{
    /**
     * Получить роут метода
     *
     * @return string
     */
    public function getRoute();

    /**
     * Получить HTTP-глагол метода
     *
     * @return string
     */
    public function getMethod();

    /**
     * Получить список oauth2-разрешений, которые должны быть у пользователя для доступа к методу
     *
     * @return string[]
     */
    public function getScopes();

    /**
     * Получить замыкание, в котором реализована логика метода
     *
     * @param Request $request
     * @return \Closure
     */
    public function getCallback(Request $request);

    /**
     * Установить пользователя для метода
     *
     * @param User $user
     */
    public function setUser(User $user);
}