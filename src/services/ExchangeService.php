<?php

namespace services;

use repositories\ExchangeRepository;

/**
 * Сервис для получения курса валют
 *
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
class ExchangeService
{
	/**
	 * @var ExchangeRepository
	 */
	private $exchangeRepository;

	/**
	 * @param ExchangeRepository $exchangeRepository
	 */
	public function __construct(ExchangeRepository $exchangeRepository)
	{
		$this->exchangeRepository = $exchangeRepository;
	}

	/**
	 * Посчитать итогую сумму с учетом курса валют
	 *
	 * @param string $sourceId
	 * @param string $targetId
	 * @param float $amount
	 * @return float
	 */
	public function calc($sourceId, $targetId, $amount)
	{
		$exchange = $this->exchangeRepository->findByPair($sourceId, $targetId);

		if (!$exchange) {
			return $rate;
		}

		return $exchange->calc($amount);
	}
}