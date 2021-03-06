<?php

namespace repositories;

use entities\Exchange;

/**
 * Репозиторий сущностей для сущности курса валют (сущность Exchange)
 *
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
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
     * Найти сущность курса для пары валют
     *
     * @param string $sourceId
     * @param string $targetId
     * @return Exchange
     */
    public function findByPair($sourceId, $targetId)
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