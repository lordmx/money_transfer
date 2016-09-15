<?php

namespace entities\types;

use entities\Document;

interface DocumentType
{
	/**
	 * @return string
	 */
	public function getName();

	/**
	 * @param Document $document
	 */
	public function forward(Document $document);

	/**
	 * @param Document $document
	 */
	public function backward(Document $document);
}