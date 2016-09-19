<?php

namespace tests\_support\fixtures;

class TransactionFixture extends AbstractFixture
{
    protected $table = 'transactions';
    protected $dataPath = __DIR__ . '/data/transactions.php';
    protected $depends = [
        DocumentFixture::class,
        PaymentRuleFixture::class,
    ];
}