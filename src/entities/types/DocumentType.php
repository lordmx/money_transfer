<?php

namespace entities\types;

use entities\Document;
use entities\User;
use dto\DtoInterface;

interface DocumentType
{
	/**
	 * @return string
	 */
	public function getName();

	/**
	 * @param Document $document
	 * @param User $user
	 * @return Document
	 */
	public function forward(Document $document, User $user);

	/**
	 * @param Document $document
	 * @return Document
	 */
	public function backward(Document $document);

	/**
	 * @param Document $document
	 * @param DtoInterface $dto
	 */
	public function initContext(Document $document, DtoInterface $dto);
}