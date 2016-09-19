<?php

namespace tests\_support\fixtures;

class UserFixture extends AbstractFixture
{
    protected $table = 'users';
    protected $dataPath = __DIR__ . '/data/users.php';
    protected $depends = [
        ScopeFixture::class,
        UserScopeFixture::class,
    ];
}