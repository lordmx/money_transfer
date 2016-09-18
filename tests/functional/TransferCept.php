<?php 

\tests\_support\fixtures\TransactionFixture::load();

$I = new FunctionalTester($scenario);
$I->wantTo('transfer money from user to user');

$I->amGoingTo('try to authorize with incorrect access token');

$I->haveHttpHeader('Authorization', 'Bearer wrong');
$I->sendPOST('users/transactions');

$I->seeResponseCodeIs(401);

$I->amGoingTo('try to transfer money via wallets withot any payment rule');

$I->haveHttpHeader('Authorization', 'Bearer test');
$I->sendPOST('users/transactions', json_encode([
	'source_wallet_id' => 1,
	'target_wallet_id' => 2,
	'target_user_id'   => 2,
	'amount'		   => 100,
]));

$I->seeResponseCodeIs(400);

$I->amGoingTo('try to transfer amount lesser than min amount of payment rule');

$I->haveHttpHeader('Authorization', 'Bearer test');
$I->sendPOST('users/transactions', json_encode([
	'source_wallet_id' => 1,
	'target_wallet_id' => 1,
	'target_user_id'   => 2,
	'amount'		   => 5,
]));

$I->seeResponseCodeIs(400);

$I->amGoingTo('try to transfer amount greater than max amount of payment rule');

$I->haveHttpHeader('Authorization', 'Bearer test');
$I->sendPOST('users/transactions', json_encode([
	'source_wallet_id' => 1,
	'target_wallet_id' => 1,
	'target_user_id'   => 2,
	'amount'		   => 2000,
]));

$I->seeResponseCodeIs(400);

$I->amGoingTo('try to transfer amount greater than users balance');

$I->haveHttpHeader('Authorization', 'Bearer test');
$I->sendPOST('users/transactions', json_encode([
	'source_wallet_id' => 1,
	'target_wallet_id' => 1,
	'target_user_id'   => 2,
	'amount'		   => 250,
]));

$I->seeResponseCodeIs(400);

$I->amGoingTo('try to transfer money to same user');

$I->haveHttpHeader('Authorization', 'Bearer test');
$I->sendPOST('users/transactions', json_encode([
	'source_wallet_id' => 1,
	'target_wallet_id' => 1,
	'target_user_id'   => 1,
	'amount'		   => 10,
]));

$I->seeResponseCodeIs(400);

$I->amGoingTo('try to correct transfer');

$I->haveHttpHeader('Authorization', 'Bearer test');
$I->sendPOST('users/transactions', json_encode([
	'source_wallet_id' => 1,
	'target_wallet_id' => 1,
	'target_user_id'   => 2,
	'amount'		   => 100,
]));

$I->seeResponseCodeIs(200);

$I->seeResponseContainsJson([
	'transactions' => [
		[
			'wallet_id' => 1,
			'user_id' => 1,
			'amount' => -110,
		],
		[
			'wallet_id' => 1,
			'user_id' => 2,
			'amount' => 100,
		],
	],
	'resultset' => [
		'metadata' => [
			'limit' => 0,
			'offset' => 0,
			'count' => 2,
		]
	],
]);