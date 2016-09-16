<?php

namespace oauth2\grants;

class BearerGrant extends AbstractGrant implements GrantInterface
{
	/**
	 * @inheritdoc
	 */
	public function createSession($token)
	{
		$user = $this->userRepository->findByAccessToken();

		if (!$user) {
			throwUnauthorizedHttpException();
		}

		$session = $this->sessionRepository->findByUser($user);

		if (!$session) {
			$session = $this->sessionRepository->createForUser($user);
		}

		return $session;
	}
}