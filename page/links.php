<?php
namespace OB1\Page;

use Gt\WebEngine\Logic\Page;
use OB1\Archive\EpisodeList;
use OB1\Archive\InterestingLink;

class LinksPage extends Page {
	public function go():void {
		$this->ensureSort();
		$links = $this->getLinks();
		$this->output($links, $this->input->getString("sort"));
	}

	private function ensureSort():void {
		if(!$this->input->contains("sort")) {
			$this->redirect("?sort=date");
		}
	}

	/** @return InterestingLink[] */
	private function getLinks():array {
		$linkArray = [];

		$episodeList = new EpisodeList();
		foreach($episodeList as $episode) {
			$links = $episode->getLinks();
			array_push($linkArray, ...$links->getArray());
		}

		return $linkArray;
	}

	/** @param InterestingLink[] $links */
	private function output(array $links, string $sort):void {
		usort(
			$links,
			fn(InterestingLink $a, InterestingLink $b) => $a->getTitle() > $b->getTitle()
		);

		switch($sort) {
		case "date":
			break;

		case "name":
			break;
		}

		$this->document->querySelector("ul.links")->bindList($links);
	}
}
