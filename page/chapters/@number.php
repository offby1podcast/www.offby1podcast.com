<?php
namespace OB1\Page\Chapters;

use Gt\WebEngine\Logic\Page;
use OB1\Archive\EpisodeFactory;
use OB1\Github\Repo;

class _NumberPage extends Page {
	public function go():void {
		header("Content-type: application/json");
		$episodeNumber = $this->dynamicPath->get("number");
		$repo = new Repo();
		$factory = new EpisodeFactory($repo);
		$episode = $factory->createFromNumber($episodeNumber);
		echo json_encode($episode->getChapters());
		exit();
	}
}
