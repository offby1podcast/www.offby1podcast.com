<?php
namespace OB1\Archive;

class ChapterList {
	/** @var Chapter[] */
	private array $chapterArray;

	public function __construct(Chapter...$chapters) {
		$this->chapterArray = $chapters;
	}
}
