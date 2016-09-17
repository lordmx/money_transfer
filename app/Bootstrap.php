<?php

namespace app;

use \Silex\Application;
use di\Container;

class Bootstrap
{
	/**
	 * @var Application
	 */
	private $app;

	/**
	 * @var Container
	 */
	private $di;

	/**
	 * @param Application $app
	 */
	public function __construct(Application $app)
	{
		$this->app = $app;
	}

	/**
	 * @param string $config
	 */
	public function init($config)
	{
		$app = $this->app;

		$app['debug'] = true;
		$app->register(new \Euskadi31\Silex\Provider\ConfigServiceProvider($config));

		$app->register(new \Silex\Provider\DoctrineServiceProvider(), [
		    'db.options' => [
		        'driver'    => 'pdo_mysql',
		        'host'      => $app['mysql']['host'],
		        'dbname'    => $app['mysql']['schema'],
		        'user'      => $app['mysql']['user'],
		        'password'  => $app['mysql']['password'],
		        'charset'   => 'utf8',
		    ],
		]);

		$this->initContainer();
	}

	/**
	 * @return Container
	 */
	public function getContainer()
	{
		return $this->di;
	}

	private function initContainer()
	{
		$di = new Container();

		// Register DBAL
		$di->register('db', $this->app['db']);

		// Register gateways
		$di->lazy('exchangeGateway', function () use ($di) {
			return new \gateways\ExchangeGateway($di->get('db'));
		});
		$di->lazy('paymentRuleGateway', function () use ($di) {
			return new \gateways\PaymentRuleGateway($di->get('db'));
		});
		$di->lazy('transactionGateway', function () use ($di) {
			return new \gateways\TransactionGateway($di->get('db'));
		});
		$di->lazy('userGateway', function () use ($di) {
			return new \gateways\UserGateway($di->get('db'));
		});
		$di->lazy('walletGateway', function () use ($di) {
			return new \gateways\WalletGateway($di->get('db'));
		});
		$di->lazy('documentGateway', function () use ($di) {
			return new \gateways\DocumentGateway($di->get('db'));
		});
		$di->lazy('sessionGateway', function () use ($di) {
			return new \oauth2\gateways\SessionGateway($di->get('db'));
		});
		$di->lazy('scopeGateway', function () use ($di) {
			return new \oauth2\gateways\ScopeGateway($di->get('db'));
		});
		$di->lazy('userScopeGateway', function () use ($di) {
			return new \oauth2\gateways\UserScopeGateway($di->get('db'));
		});

		// Register repositories
		$di->lazy('documentRepository', function () use ($di) {
			$typeTransfer = new \entities\types\TransferDocumentType(
				$di->get('transactionRepository'),
				$di->get('paymentRuleRepository'),
				$di->get('userRepository'),
				$di->get('balanceService'),
				$di->get('exchangeService')
			);

			$types = [
				entities\Document::TYPE_TRANSFER => $typeTransfer,
			];

			return new \repositories\DocumentRepository(
				$di->get('documentGateway'),
				$di->get('userRepository'),
				$types
			);
		});
		$di->lazy('exchangeRepository', function () use ($di) {
			return new \repositories\ExchangeRepository(
				$di->get('exchangeGateway')
			);
		});
		$di->lazy('paymentRuleRepository', function () use ($di) {
			return new \repositories\PaymentRuleRepository(
				$di->get('paymentRuleGateway'),
				$di->get('walletRepository')
			);
		});
		$di->lazy('transactionRepository', function () use ($di) {
			return new \repositories\TransactionRepository(
				$di->get('transactionGateway'),
				$di->get('userRepository'),
				$di->get('walletRepository')
			);
		});
		$di->lazy('userRepository', function () use ($di) {
			return new \repositories\UserRepository(
				$di->get('userGateway')
			);
		});
		$di->lazy('walletRepository', function () use ($di) {
			return new \repositories\WalletRepository(
				$di->get('walletGateway')
			);
		});
		$di->lazy('scopeRepository', function () use ($di) {
			return new \oauth2\repositories\ScopeRepository(
				$di->get('scopeGateway')
			);
		});
		$di->lazy('sessionRepository', function () use ($di) {
			return new \oauth2\repositories\SessionRepository(
				$di->get('sessionGateway'),
				$di->get('userScopeGateway'),
				$di->get('scopeRepository'),
				$di->get('userRepository')
			);
		});

		// Register services
		$di->lazy('balanceService', function () use ($di) {
			return new \services\BalanceService(
				$di->get('transactionRepository')
			);
		});
		$di->lazy('exchangeService', function () use ($di) {
			return new \services\ExchangeService(
				$di->get('exchangeRepository')
			);
		});
		$di->lazy('transferService', function () use ($di) {
			return new \services\TransferService(
				$di->get('documentRepository')
			);
		});

		$this->di = $di;
	}
}