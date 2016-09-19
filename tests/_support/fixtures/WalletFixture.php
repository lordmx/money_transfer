<?php

namespace tests\_support\fixtures;

class WalletFixture extends AbstractFixture
{
    protected $table = 'wallets';
    protected $dataPath = __DIR__ . '/data/wallets.php';
}