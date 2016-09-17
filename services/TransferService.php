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

		$document = new Document();
		$document->setCreator($user);
		$document->setType($this->documentRepository->getType(Document::TYPE_TRANSFER));
		$document->setCreatedAt(new \DateTime());
		$document->setNotice($dto->getNotice());

		$this->transferDocumentType->initContext($document, $dto);
		$this->documentRepository->save($document);

		$document->execute();

		$this->documentRepository->save($document);

		return $document;
	}
}