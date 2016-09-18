<?php

namespace gateways;

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