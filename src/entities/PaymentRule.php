<?php

namespace entities;

/**
 * Платежной правило - это правило, которое определяет допустимость движения средств между кошельками пользователей: если нет правила,
 * которое связывает кошелек А и кошелек Б, то движения средств между этими кошельками не допустимо. Кроме того, платежные правила 
 * определяют минимальную и максимальную сумму разового движения, возможную комиссию и специальный курс для мультивалютных операций.
 *
 * В дальнейшем платежные правила можно расширить добавив в них, например, функцию регулирования движения средств не только между 
 * двумя кошельками пользователей, но и между кошельком пользователя и платежной системой, разрешая или запрещая тем самым ввод
 * или вывод средств в/из определенной платежной системы, определяя дополнительную комиссию к операции, определяя минимальную.
 * сумму ввода или максимальную сумму вывода.
 *
 * Также можно ввести понятие платежной операции, которая будет характеризовать движения средст. Соответственно платежное правило
 * будет хранить две таких операции: для исходного кошелька и результирующего.
 *
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
class PaymentRule extends AbstractEntity implements Entity
{
    /**
     * @var int
    */
    private $id;

    /**
     * @var Wallet
     */
    private $souceWallet;

    /**
     * @var Wallet
     */
    private $targetWallet;

    /**
     * Минимальная сумма разового движения
     *
     * @var float|null
     */
    private $minAmount;

    /**
     * Максимальная сумма разового движения
     *
     * @var float|null
     */
    private $maxAmount;

    /**
     * Дополнительная комиссия к операции
     *
     * @var float
     */
    private $commission = 0.0;

    /**
     * Специальный курс для мультивалютных операций, проводимых через данное правило
     *
     * @var float|null
     */
    private $crossRate;

    /**
     * @inheritdoc
     */
    public function validate()
    {
        if (!$this->sourceWallet) {
            $this->errors[] = 'Source wallet is empty or missing';
        }

        if (!$this->targetWallet) {
            $this->errors[] = 'Target wallet is empty or missing';
        }

        if ($this->minAmount < 0) {
            $this->errors[] = 'Minimal amount must be greater or equals null';
        }

        if (!is_null($this->maxAmount) && $this->maxAmount <= 0) {
            $this->errors[] = 'Maximum amount must be greater than null';
        }

        if ($this->crossRate < 0) {
            $this->errors[] = 'Cross rate must be greater or equals null';
        }

        return empty($this->errors);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Wallet
     */
    public function getSourceWallet()
    {
        return $this->sourceWallet;
    }

    /**
     * @return Wallet
     */
    public function getTargetWallet()
    {
        return $this->targetWallet;
    }

    /**
     * @return float|null
     */
    public function getMinAmount()
    {
        return $this->minAmount;
    }

    /**
     * @return float|null
     */
    public function getMaxAmount()
    {
        return $this->maxAmount;
    }

    /**
     * @return float
     */
    public function getCommission()
    {
        return $this->commission;
    }

    /**
     * @return float|null
     */
    public function getCrossRate()
    {
        return $this->crossRate;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param Wallet $wallet
     */
    public function setSourceWallet($wallet)
    {
        $this->sourceWallet = $wallet;
    }

    /**
     * @param Wallet $wallet
     */
    public function setTargetWallet($wallet)
    {
        $this->targetWallet = $wallet;
    }

    /**
     * @param float $amount
     */
    public function setMinAmount($amount)
    {
        $this->minAmount = $amount;
    }

    /**
     * @param float $amount
     */
    public function setMaxAmount($amount)
    {
        $this->maxAmount = $amount;
    }

    /**
     * @param float $commission
     */
    public function setCommission($commission)
    {
        $this->commission = $commission;
    }

    /**
     * @param float $rate
     */
    public function setCrossRate($rate)
    {
        $this->crossRate = $rate;
    }

    /**
     * @inheritdoc
     */
    public function load(array $map)
    {
        if (isset($map['id'])) {
            $this->setId((int)$map['id']);
        }

        if (isset($map['maxAmount'])) {
            $this->setMaxAmount((float)$map['maxAmount']);
        }

        if (isset($map['minAmount'])) {
            $this->setMinAmount((float)$map['minAmount']);
        }

        if (isset($map['commission'])) {
            $this->setCommission((float)$map['commission']);
        }

        if (isset($map['crossRate'])) {
            $this->setCrossRate((float)$map['crossRate']);
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
                'sourceWalletId' => $this->getSourceWallet()->getId(),
                'targetWalletId' => $this->getTargetWallet()->getId(),
                'minAmount' => $this->getMinAmount(),
                'maxAmount' => $this->getMaxAmount(),
                'commission' => $this->getCommission(),
                'crossRate' => $this->getCrossRate(),
            ];
        }

        return [];
    }
}