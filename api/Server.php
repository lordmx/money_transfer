<?php

namespace api;

use oauth2\Server as Oauth2Server;
use api\handlers\HandlerInterface;
use Silex\Application;
use \Symfony\Component\HttpFoundation\Request;
use entities\User;
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
	 * @var User
	 */
	private $user;

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
		$this->app->before(function (Request $request, Application $application) {
			$session = $this->oauth2->createSession(new FoundationAdapterRequest($request));
			$this->user = $session->getUser();
		});
	}

	/**
	 * @param string $method
	 * @param array $args
	 * @throws MethodNotAllowedHttpException
	 */
	public function __call($method, $args)
	{
		if (!in_array(strtoupper($method), self::$allowedMethods)) {
			throw new MethodNotAllowedHttpException();
		}

		if (count($args) < 2) {
			throw new BadRequestHttpException();
		}

		$this->registerHandler($method, $args[0], $args[1]);
	}

	/**
	 * @param string $method
	 * @param string $route
	 * @param HandlerInterface $handler
	 */
	private function registerHandler($method, $route, HandlerInterface $handler)
	{
		$route = $this->prefix . $this->version . $route;
		$this->app->$method($route, $handler->getCallback($this->user, $this->request));
	}
}