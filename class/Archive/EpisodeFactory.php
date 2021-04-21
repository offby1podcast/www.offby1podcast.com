<?php
namespace OB1\Archive;

use DateTimeImmutable;
use DateTimeInterface;
use OB1\Github\Repo;

class EpisodeFactory {
	public function __construct(
		private Repo $repo
	) {}

	public function createFromNumber(int $episodeNumber):Episode {
		$sha = $this->repo->getShaForEpisode($episodeNumber);
		$showNotes = $this->repo->getBlobContent($sha);
		$title = $this->parseTitle($showNotes);
		$releaseDate = $this->parseReleaseDate($showNotes);
		$chapterList = $this->parseChapters($title, $showNotes);
		$linkList = $this->parseLinks($showNotes, $episodeNumber, $releaseDate);

		return new Episode(
			$episodeNumber,
			$title,
			$releaseDate,
			$showNotes,
			$chapterList,
			$linkList
		);
	}

	/**
	 * @param object[] $fileList Each file from Github's API with the
	 * properties: path, mode, type, sha, size and url.
	 */
	public function createFromFileList(
		int $episodeNumber,
		array $fileList
	):Episode {
		$showNotes = "";

		foreach($fileList as $file) {
			$extension = pathinfo(
				$file->path,
				PATHINFO_EXTENSION
			);
			if($extension === "md") {
				$showNotes = $this->repo->getBlobContent($file->sha);
			}
		}

		$title = $this->parseTitle($showNotes);
		$releaseDate = $this->parseReleaseDate($showNotes);
		$chapterList = $this->parseChapters($title, $showNotes);
		$linkList = $this->parseLinks($showNotes, $episodeNumber, $releaseDate);

		return new Episode(
			$episodeNumber,
			$title,
			$releaseDate,
			$showNotes,
			$chapterList,
			$linkList
		);
	}

	public function parseTitle(string $notes):string {
		$title = ltrim($notes, " \t\n\r\0\x0B#");
		$title = strtok($title, "\n");
		return $title;
	}

	public function parseReleaseDate(string $notes):DateTimeInterface {
		foreach(explode("\n", $notes) as $line) {
			$line = trim($line);
			if(str_starts_with($line, "Released: ")) {
				return new DateTimeImmutable(substr($line, strpos($line, ":") + 1));
			}
		}
	}

	public function parseChapters(string $title, string $notes):ChapterList {
		$chaptersArray = [];
		$previousLine = "";

		foreach(explode("\n", $notes) As $line) {
			$line = trim($line);
			if(preg_match("/^\((?P<timestamp>\d+:\d+)\)/", $line, $matches)) {
				array_push(
					$chaptersArray,
					new Chapter($matches["timestamp"], $previousLine)
				);
			}

			if(strlen($line) > 0) {
				$previousLine = trim($line, "# ");
			}
		}

		return new ChapterList($title, ...$chaptersArray);
	}

	public function parseLinks(
		string $notes,
		int $episodeNumber,
		DateTimeInterface $episodeReleaseDate
	):InterestingLinkList {
		$linksArray = [];

		$notes = substr($notes, strpos($notes, "## Interesting links"));
		foreach(explode("\n", $notes) as $line) {
			$line = trim($line);
			if(preg_match("/(?P<person>Greg|Jake):? \[(?P<title>[^\]]+)\]\((?P<url>[^\)]+)\)/i", $line, $matches)) {
				array_push(
					$linksArray,
					new InterestingLink(
						$matches["title"],
						$matches["url"],
						$matches["person"],
						$episodeNumber,
						$episodeReleaseDate
					)
				);
			}
		}

		return new InterestingLinkList(...$linksArray);
	}
}
