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
	 * @var TransferDocumentType
	 */
	private $transferDocumentType;

	/**
	 * @param DocumentRepository $documentRepository
	 * @param TransferDocumentType $transferDocumentType
	 */
	public function __construct(DocumentRepository $documentRepository, TransferDocumentType $transferDocumentType)
	{
		$this->documentRepository = $documentRepository;
		$this->transferDocumentType = $transferDocumentType;
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
		$document->setType($this->transferDocumentType);
		$document->setCreatedAt(new \DateTime());
		$document->setNotice($dto->getNotice());

		$this->transferDocumentType->initContext($document, $dto);
		$this->documentRepository->save($document);

		$document->forward();

		$this->documentRepository->save($document);

		return $document;
	}
}