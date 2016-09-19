<?php

namespace tests\_support\fixtures;

class ScopeFixture extends AbstractFixture
{
    protected $table = 'oauth2_scopes';
    protected $dataPath = __DIR__ . '/data/scopes.php';
}