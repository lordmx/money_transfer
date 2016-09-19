<?php 

\tests\_support\fixtures\TransactionFixture::load();

$I = new FunctionalTester($scenario);
$I->wantTo('get transactions history');

$I->haveHttpHeader('Authorization', 'Bearer test');
$I->sendGET('users/transactions');

$I->seeResponseCodeIs(200);

$I->seeResponseContainsJson([
    'resultset' => [
        'metadata' => [
            'limit' => 10,
            'offset' => 0,
            'count' => 3,
        ]
    ],
    "transactions" => [
        [
          "id" => 1,
          "amount" => 100,
          "wallet_id" => 1,
          "document_id" => 1,
          "user_id" => 1
        ],
        [
          "id" => 2,
          "amount" => 100,
          "wallet_id" => 1,
          "document_id" => 1,
          "user_id" => 1
        ],
        [
          "id" => 3,
          "amount" => -50,
          "wallet_id" => 1,
          "document_id" => 1,
          "user_id" => 1
        ]
      ]
]);