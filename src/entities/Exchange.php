<?php

namespace entities;

/**
 * Сущность, которая хранит информацию о соотношении курса обной валюты к другой (курса валют).
 *
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
class Exchange extends AbstractEntity implements Entity
{
	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var string
	 */
	private $sourceCurrencyId;

	/**
	 * @var string
	 */
	private $targetCurrencyId;

	/**
	 * @var float
	 */
	private $rate;

	/**
	 * @inheritdoc
	 */
	public function validate()
	{
		if (!$this->sourceCurrencyId) {
			$this->errors[] = 'Source currency ISO code is missing or empty';
		}

		if (!$this->targetCurrencyId) {
			$this->errors[] = 'Target currency ISO code is missing or empty';
		}

		if (!is_null($this->rate) && $this->rate <= 0) {
			$this->errors[] = 'Rate must be greater than null';
		}
	}

	/**
	 * @param float $amount
	 * @return float
	 */
	public function calc($amount)
	{
		return $amount * $this->rate;
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getSourceCurrencyId()
	{
		return $this->sourceCurrencyId;
	}

	/**
	 * @return string
	 */
	public function getTargetCurrencyId()
	{
		return $this->targetCurrencyId;
	}

	/**
	 * @return float
	 */
	public function getRate()
	{
		return $this->rate;
	}

	/**
	 * @param int $id
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * @param string $currencyId
	 */
	public function setSourceCurrencyId($currencyId)
	{
		$this->sourceCurrencyId = $currencyId;
	}

	/**
	 * @param string $currencyId
	 */
	public function setTargetCurrencyId($currencyId)
	{
		$this->targetCurrencyId = $currencyId;
	}

	/**
	 * @param float $rate
	 */
	public function setRate($rate)
	{
		$this->rate = $rate;
	}

	/**
	 * @inheritdoc
	 */
	public function load(array $map)
	{
		if (isset($map['id'])) {
			$this->setId((int)$map['id']);
		}

		if (isset($map['sourceCurrencyId'])) {
			$this->setSourceCurrencyId($map['sourceCurrencyId']);
		}

		if (isset($map['targetCurrencyId'])) {
			$this->setTargetCurrencyId($map['targetCurrencyId']);
		}

		if (isset($map['rate'])) {
			$this->setRate($map['rate']);
		}
	}

	/**
	 * @inheritdoc
	 */
	public function toMap()
	{
		if ($this->validate()) {
			return [
				'id' => $this->getId(),
				'sourceCurrencyId' => $this->getSourceCurrencyId(),
				'targetCurrencyId' => $this->getTargetCurrencyId(),
				'rate' => $this->getRate(),
			];
		}

		return [];
	}
}