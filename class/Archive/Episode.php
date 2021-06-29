<?php
namespace OB1\Archive;

use DateTimeInterface;
use Gt\DomTemplate\BindDataMapper;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\GithubFlavoredMarkdownConverter;

class Episode implements BindDataMapper {
	private string $intro;
	private string $content;

	public function __construct(
		private int $number,
		private string $title,
		private DateTimeInterface $releaseDate,
		private string $showNotes,
		private ChapterList $chapters,
		private InterestingLinkList $links
	) {
// Set the intro to the first paragraph after the title.
		preg_match(
			"/#.+\n+(?P<paragraph>.+)\n/",
			$this->showNotes,
			$matches
		);
		$this->intro = $matches["paragraph"] ?? "Episode $number, $title";
		$this->content = substr($this->showNotes, strpos($this->showNotes, "##"));
		$this->content = str_replace("(img/", "(https://raw.githubusercontent.com/offby1podcast/archive/master/img/", $this->content);
	}

	public function bindDataMap():array {
		$markdown = new GithubFlavoredMarkdownConverter();

		return [
			"number" => $this->getPaddedNumber(),
			"url" => $this->getUrl(),
			"mp3-url" => $this->getMp3Url(),
			"title" => $this->title,
			"release-date" => $this->releaseDate->format("Y-m-d H:i:s"),
			"release-date-friendly" => $this->releaseDate->format("jS F Y"),
			"show-notes" => $this->showNotes,
			"intro" => $this->intro,
			"content" => $markdown->convertToHtml($this->content),
		];
	}

	public function getNumber():int {
		return $this->number;
	}

	public function getPaddedNumber():string {
		return str_pad($this->getNumber(), 3, "0", STR_PAD_LEFT);
	}

	public function getTitle():string {
		return $this->title;
	}

	public function getTitleWithNumber():string {
		return $this->getPaddedNumber() . " - " . $this->getTitle();
	}

	public function getIntro():string {
		return $this->intro;
	}

	public function getUrl():string {
		return "https://www.offby1podcast.com/" . $this->getPaddedNumber();
	}

	public function getMp3Url() {
		return $this->getUrl() . ".mp3";
	}

	public function getPubDateString():string {
		return $this->releaseDate->format("D, d M Y H:i:s O");
	}

	public function getReleaseDate():DateTimeInterface {
		return $this->releaseDate;
	}

	public function getLinks():InterestingLinkList {
		return $this->links;
	}

	public function getShowNotes(bool $html = false):string {
		if(!$html) {
			return $this->showNotes;
		}

		$markdown = new CommonMarkConverter();
		return $markdown->convertToHtml($this->showNotes);
	}

	public function getChapters():ChapterList {
		return $this->chapters;
	}
}
