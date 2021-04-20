<?php
namespace OB1\Archive;

use Iterator;
use OB1\Github\Repo;

class EpisodeList implements Iterator {

	const SORT_DATE_DESC = "date-desc";
	const SORT_DATE_ASC = "date-asc";

	private int $iteratorIndex;
	/** @var Episode[] */
	private array $episodeList;
	private Repo $repo;

	public function __construct(string $sort = self::SORT_DATE_DESC) {
		$this->iteratorIndex = 0;
		$this->repo = new Repo();
		$this->episodeList = $this->buildList($this->repo->getTree());
		$this->sort($sort);
	}

	public function current():Episode {
		return $this->episodeList[$this->iteratorIndex];
	}

	public function next():void {
		$this->iteratorIndex++;
	}

	public function key():int {
		return $this->iteratorIndex;
	}

	public function valid():bool {
		return isset($this->episodeList[$this->iteratorIndex]);
	}

	public function rewind():void {
		$this->iteratorIndex = 0;
	}

	/** @param object[] $jsonList The tree from Github's API. */
	private function buildList(array $jsonList):array {
		$episodeList = [];
		$foundFileList = [];

// Loop over the list of blobs from the Github repo, building up a list of
// files for each episode.
		foreach($jsonList as $item) {
			if($item->type !== "blob") {
				continue;
			}
			if(!preg_match("/^(?P<filename>(?P<num>\d+)[^\.]+(\.(.+))?)/", $item->path, $matches)) {
				continue;
			}

			$num = (int)$matches["num"];
			if(!isset($foundFileList[$num])) {
				$foundFileList[$num] = [];
			}

			array_push($foundFileList[$num], $item);
		}

// The found file list is sorted by episode number, but Github may not provide
// the list in any particular order - sort by key.
		ksort($foundFileList, SORT_NUMERIC);

// Now loop over the found files and build up the list of Episode objects:
		$episodeFactory = new EpisodeFactory($this->repo);
		foreach($foundFileList as $episodeNumber => $fileList) {
			$episode = $episodeFactory->createFromFileList(
				$episodeNumber,
				$fileList
			);
			array_push($episodeList, $episode);
		}

		return $episodeList;
	}

	private function sort(string $direction):void {
		switch($direction) {
		case self::SORT_DATE_ASC:
			usort(
				$this->episodeList,
				fn(Episode $a, Episode $b) => $a->getReleaseDate() > $b->getReleaseDate()
			);
			break;

		case self::SORT_DATE_DESC:
			usort(
				$this->episodeList,
				fn(Episode $a, Episode $b) => $a->getReleaseDate() <= $b->getReleaseDate()
			);
			break;
		}
	}
}
