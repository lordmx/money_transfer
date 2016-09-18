<?php 

\tests\_support\fixtures\TransactionFixture::load();

$I = new FunctionalTester($scenario);
$I->wantTo('get users balance');

$I->haveHttpHeader('Authorization', 'Bearer test');
$I->sendGET('users/balance');

$I->seeResponseCodeIs(200);

$I->seeResponseContainsJson([
	'balance' => [
		[
			'wallet_id' => 1,
			'balance' => 150,
			'currency_id' => 'RUB',
		],
		[
			'wallet_id' => 2,
			'balance' => 0,
			'currency_id' => 'RUB',
		],
	],
	'resultset' => [
		'metadata' => [
			'limit' => 10,
			'offset' => 0,
			'count' => 2,
		]
	],
]);