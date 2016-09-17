<?php

namespace repositories;

use entities\Exchange;

class ExchangeRepository extends AbstractRepository implements RepositoryInterface
{
	/**
	 * @inheritdoc
	 */
	protected function createEntity()
	{
		return new Exchange();
	}

	/**
	 * @param string $sourceId
	 * @param string $targetId
	 * @return Exchange
	 */
	public function findForPair($sourceId, $targetId)
	{
		if ($sourceId == $targetId) {
			$exchange = new Exchange();

			$exchange->setTargetCurrencyId($sourceId);
			$exchange->setSourceCurrencyId($sourceId);
			$exchange->setRate(1.0);

			return $exchange;
		}

		$criteria = [
			'sourceCurrencyId' => $sourceId,
			'targetCurrencyId' => $targetId,
		];

		$rows = $this->gateway->findByCriteria($criteria);

		if (!$rows) {
			return null;
		}

		return $this->populateEntity($rows[0]);
	}
}