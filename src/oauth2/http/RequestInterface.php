<?php

namespace oauth2\http;

/**
 * Интерфейс http-запроса к oauth2-серверу
 *
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
interface RequestInterface
{
    /**
     * Получить аргумент запроса
     *
     * @param string $key
     * @return mixed
     */
    public function getParam($key);

    /**
     * Получить заголовок запроса
     *
     * @param string $name
     * @return string
     */
    public function getHeader($name);
}