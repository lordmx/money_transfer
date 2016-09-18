<?php

namespace oauth2\grants;

/**
 * Авторизация пользователя через oauth2 bearer grant со статическим токеном доступа
 *
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
class BearerGrant extends AbstractGrant implements GrantInterface
{
	/**
	 * @inheritdoc
	 */
	public function createSession($token)
	{
		$user = $this->userRepository->findByAccessToken($token);

		if (!$user) {
			$this->throwUnauthorizedHttpException();
		}

		$session = $this->sessionRepository->findByUser($user);

		if (!$session) {
			$session = $this->sessionRepository->createForUser($user);
		}

		return $session;
	}
}