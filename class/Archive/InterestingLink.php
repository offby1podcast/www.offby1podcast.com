<?php
namespace OB1\Archive;

use DateTimeInterface;
use Gt\DomTemplate\BindDataMapper;

class InterestingLink implements BindDataMapper {
	public function __construct(
		private string $title,
		private string $url,
		private string $person,
		private int $episodeNumber,
		private DateTimeInterface $episodeReleaseDate
	) { }

	public function getTitle():string {
		return $this->title;
	}

	public function getUrl():string {
		return $this->url;
	}

	public function getPerson():string {
		return $this->person;
	}

	public function getEpisodeNumber():int {
		return $this->episodeNumber;
	}

	public function getReleaseDate():DateTimeInterface {
		return $this->episodeReleaseDate;
	}

	public function bindDataMap():array {
		return [
			"title" => $this->getTitle(),
			"url" => $this->getUrl(),
			"person" => $this->getPerson(),
			"episode-number" => $this->getEpisodeNumber(),
			"release-date" => $this->getReleaseDate()->format("Y-m-d"),
			"release-date-friendly" => $this->getReleaseDate()->format("jS F Y"),
		];
	}
}
