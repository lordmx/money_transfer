<?php

namespace tests\_support\fixtures;

class DocumentFixture extends AbstractFixture
{
    protected $table = 'documents';
    protected $dataPath = __DIR__ . '/data/documents.php';
    protected $depends = [
        UserFixture::class,
        WalletFixture::class,
    ];
}