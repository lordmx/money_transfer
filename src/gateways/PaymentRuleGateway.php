<?php

namespace gateways;

class PaymentRuleGateway extends AbstractGateway implements GatewayInterface
{
	/**
	 * @inheritdoc
	 */
	public function getTable()
	{
		return 'payment_rules';
	}
}