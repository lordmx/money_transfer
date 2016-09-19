<?php

namespace gateways;

/**
 * Шлюз для таблицы платежных правил (payment_rules)
 *
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
class PaymentRuleGateway extends AbstractGateway implements GatewayInterface
{
    /**
     * @inheritdoc
     */
    protected function getTable()
    {
        return 'payment_rules';
    }
}