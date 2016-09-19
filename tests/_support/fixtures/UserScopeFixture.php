<?php

namespace tests\_support\fixtures;

class UserScopeFixture extends AbstractFixture
{
    protected $table = 'oauth2_user_scopes';
    protected $dataPath = __DIR__ . '/data/userScopes.php';
}