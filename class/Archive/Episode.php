<?php
namespace OB1\Archive;

use DateTimeInterface;
use Gt\DomTemplate\BindDataMapper;
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
	}

	public function bindDataMap():array {
		$markdown = new GithubFlavoredMarkdownConverter();

		return [
			"number" => str_pad($this->number, 3, "0", STR_PAD_LEFT),
			"title" => $this->title,
			"release-date" => $this->releaseDate->format("Y-m-d H:i:s"),
			"release-date-friendly" => $this->releaseDate->format("jS F Y"),
			"show-notes" => $this->showNotes,
			"intro" => $this->intro,
			"content" => $markdown->convertToHtml($this->content),
		];
	}

	public function getReleaseDate():DateTimeInterface {
		return $this->releaseDate;
	}
}
