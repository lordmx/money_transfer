<?php

namespace tests\_support\fixtures;

class PaymentRuleFixture extends AbstractFixture
{
    protected $table = 'payment_rules';
    protected $dataPath = __DIR__ . '/data/paymentRules.php';
}