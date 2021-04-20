<?php
namespace OB1\Archive;

use JsonSerializable;

class JsonArchive implements JsonSerializable {
	public function __construct() {
		$episodeIterator = new EpisodeList();
	}

	public function jsonSerialize() {
		return (object)[
			"test" => "thing",
		];
	}
}
