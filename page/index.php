<?php
namespace OB1\Page;

use Gt\WebEngine\Logic\Page;
use OB1\Archive\EpisodeIterator;

class IndexPage extends Page {
	public function go():void {
		$this->outputEpisodes();
	}

	private function outputEpisodes():void {
		$iterator = new EpisodeIterator();
	}
}
