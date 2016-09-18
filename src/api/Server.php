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
	 * @var Oauth2Server
	 */
	private $oauth2;

	/**
	 * @var array
	 */
	private $handlers = [];

	/**
	 * @var Session
	 */
	private $session;

	/**
	 * @var Request
	 */
	private $request;

	/**
	 * @var string
	 */
	private $prefix = '/api/';

	/**
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

	public function init()
	{
		$app = $this->app;

		$app->before(function (Request $request, Application $application) {
			$session = $this->oauth2->createSession(new FoundationAdapterRequest($request));
			$this->session = $session;

			foreach ($this->handlers as $handler) {
				$handler->setUser($session->getUser());
			}
		});

		$app->after(function (Request $request, Response $response) {
			$response->headers->set('Content-Type', 'application/json');
			return $response;
		});

		$app->error(function (\Exception $e, Request $request, $code) use ($app) {
		    $message = $e->getMessage();

		    switch ($code) {
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