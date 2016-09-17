<?php

namespace services;

use repositories\ExchangeRepository;

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