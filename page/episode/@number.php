<?php
namespace OB1\Page\Episode;

use Gt\WebEngine\Logic\Page;
use OB1\Archive\EpisodeFactory;
use OB1\Github\Repo;

class _NumberPage extends Page {
	public function go():void {
		$episodeNumber = $this->dynamicPath->get("number");
		$this->output($episodeNumber);
	}

	private function output(string $episodeNumber):void {
		$repo = new Repo();
		$factory = new EpisodeFactory($repo);
		$episode = $factory->createFromNumber($episodeNumber);
		$this->document->bindData($episode);
	}
}
