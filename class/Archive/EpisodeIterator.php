<?php
namespace OB1\Archive;

use Iterator;

class EpisodeIterator implements Iterator {
	const GITHUB_TREE_URL = "https://api.github.com/repos/offbyonepodcast/archive/git/trees/master?recursive=1";
	private int $iteratorIndex;

	public function __construct() {
		$this->iteratorIndex = 0;
		$ch = curl_init(self::GITHUB_TREE_URL);
		curl_setopt($ch, CURLOPT_USERAGENT, "www.offBy1podcast.com episode iterator");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$contents = curl_exec($ch);
// TODO: Store the paths that start with the correct directory.
	}

	public function current():Episode {
		// TODO: Implement current() method.
	}

	public function next():void {
		// TODO: Implement next() method.
	}

	public function key():int {
		// TODO: Implement key() method.
	}

	public function valid():bool {
		// TODO: Implement valid() method.
	}

	public function rewind():void {
		// TODO: Implement rewind() method.
	}
}
