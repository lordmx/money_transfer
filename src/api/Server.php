<?php

namespace api;

use oauth2\Server as Oauth2Server;
use api\handlers\HandlerInterface;
use Silex\Application;
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;
use oauth2\entities\Session;
use oauth2\http\FoundationAdapterRequest;
use api\Metadata;
use \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use \Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * REST-сервер для обработки API-запросов.
 *
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
class Server
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_DELETE = 'DELETE';
    const METHOD_PUT = 'PUT';

    /**
     * @var string[]
     */
    private static $allowedMethods = [
        self::METHOD_GET,
        self::METHOD_POST,
        self::METHOD_DELETE,
        self::METHOD_PUT,
    ];

    /**
     * @var Application
     */
    private $app;

    /**
     * Oauth2 сервер для авторизации пользователя
     *
     * @var Oauth2Server
     */
    private $oauth2;

    /**
     * Зарегистрированные обработчики методов
     *
     * @var array
     */
    private $handlers = [];

    /**
     * Oauth2-сессия текущего пользователя
     *
     * @var Session
     */
    private $session;

    /**
     * Текущий HTTP-запрос
     *
     * @var Request
     */
    private $request;

    /**
     * Префикс для URL запроса
     *
     * @var string
     */
    private $prefix = '/api/';

    /**
     * Версия API
     *
     * @var string
     */
    private $version = 'v1';

    /**
     * @param Application $app
     * @param Oauth2Server $oauth2
     * @param Request $request
     */
    public function __construct(Application $app, Oauth2Server $oauth2, Request $request)
    {
        $this->oauth2 = $oauth2;
        $this->app = $app;
        $this->request = $request;
    }

    /**
     * Инициализация middleware для REST-сервера
     */
    public function init()
    {
        $app = $this->app;

        // получения текущего пользователя через oauth2 bearer grant до начала обработки запроса
        $app->before(function (Request $request, Application $application) {
            $session = $this->oauth2->createSession(new FoundationAdapterRequest($request));
            $this->session = $session;

            foreach ($this->handlers as $handler) {
                $handler->setUser($session->getUser());
            }
        });

        // представление ответа API-метода в виде JSON (через HTTP-заголовок)
        $app->after(function (Request $request, Response $response) {
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        });

        // правило обработки ошибок и формирования ответа на ошибку
        $app->error(function (\Exception $e, Request $request, $code) use ($app) {
            $message = $e->getMessage();

            switch ($code) {
                case 500:
                    if (!$message) {
                        $message = 'Internal server error';
                    }

                    break;
                case 400:
                    if (!$message) {
                        $message = 'Bad request';
                    }

                    break;
                case 404:
                    $message = 'Not found';
                    break;
                case 401:
                    $message = 'Unauthorized';
                    break;
                case 403:
                    $message = 'Access denied';
                    break;
            }

            $response = new \Symfony\Component\HttpFoundation\JsonResponse();
            $response->setContent(json_encode(['resultset' => ['error' => $message, 'code' => $code]]));
            $response->setStatusCode($code);
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        });
    }

    /**
     * @param string $prefix
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * @param string $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * Регистрация обработчика метода
     *
     * @param HandlerInterface $handler
     */
    public function registerHandler(HandlerInterface $handler)
    {
        $method = strtolower($handler->getMethod());
        $route = $handler->getRoute();

        $route = $this->prefix . $this->version . $route;
        $this->app->$method($route, $handler->getCallback($this->request));
        $this->handlers[] = $handler;
    }
}