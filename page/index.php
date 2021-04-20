<?php
namespace OB1\Page;

use Gt\WebEngine\Logic\Page;
use OB1\Archive\EpisodeList;

class IndexPage extends Page {
	public function go():void {
		$this->outputEpisodes();
	}

	private function outputEpisodes():void {
		$this->document->querySelector(".c-episode-list")->bindList(
			new EpisodeList($this->input->getString("sort") ?? EpisodeList::SORT_DATE_DESC)
		);
	}
}
