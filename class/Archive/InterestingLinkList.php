<?php
namespace OB1\Archive;

class InterestingLinkList {
	/** @var InterestingLink[] */
	private array $linksArray;

	public function __construct(InterestingLink...$linksArray) {
		$this->linksArray = $linksArray;
	}

	/** @return InterestingLink[] */
	public function getArray():array {
		return $this->linksArray;
	}
}
