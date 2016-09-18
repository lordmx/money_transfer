<?php

namespace services;

use repositories\DocumentRepository;
use entities\types\TransferDocumentType;
use entities\Document;
use entities\User;
use dto\TransferDto;

class TransferService
{
	/**
	 * @var DocumentRepository
	 */
	private $documentRepository;

	/**
	 * @param DocumentRepository $documentRepository
	 */
	public function __construct(DocumentRepository $documentRepository)
	{
		$this->documentRepository = $documentRepository;
	}

	/**
	 * @param User $user
	 * @param TransferDto $dto
	 * @return Document
	 */
	public function transfer(User $user, TransferDto $dto)
	{
		$dto->setSourceUserId($user->getId());
		$type = $this->documentRepository->getType(Document::TYPE_TRANSFER);

		$document = new Document();
		$document->setCreator($user);
		$document->setType($type);
		$document->setCreatedAt(new \DateTime());
		$document->setNotice($dto->getNotice());
		$document->setStatus(Document::STATUS_CREATED);

		$type->initContext($document, $dto);
		$this->documentRepository->save($document);

		$document->execute($user);

		$this->documentRepository->save($document);

		return $document;
	}
}