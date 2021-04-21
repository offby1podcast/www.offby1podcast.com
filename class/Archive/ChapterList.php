<?php
namespace OB1\Archive;

use JsonSerializable;

class ChapterList implements JsonSerializable {
	/** @var Chapter[] */
	private array $chapterArray;

	public function __construct(
		private string $title,
		Chapter...$chapters
	) {
		$this->chapterArray = $chapters;
	}

	public function jsonSerialize():object {
		$array = [];
		foreach($this->chapterArray as $chapter) {
			array_push($array, (object)[
				"startTime" => $chapter->getSeconds(),
				"title" => $chapter->getTitle(),
			]);
		}

		return (object)[
			"version" => "1.1.0",
			"author" => "Jake Lacey, Greg Bowler",
			"title" => $this->title,
			"podcastName" => "Off By 1 Podcast",
			"chapters" => $array,
		];
	}
}
