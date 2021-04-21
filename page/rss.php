<?php
namespace OB1\Page;

use Gt\Dom\XMLDocument;
use Gt\WebEngine\Logic\Page;
use OB1\Archive\EpisodeList;
use OB1\Github\Repo;

class RssPage extends Page {
	public function go():void {
		header("Content-type: application/xml");
		$xmlContent = file_get_contents(__DIR__ . "/rss.xml");
		$document = new XMLDocument($xmlContent);
		$document->strictErrorChecking = false;

		$channelNode = $document->querySelector("channel");
		$itemTemplate = $document->querySelector("item");
		$itemTemplate->remove();

		$episodeList = new EpisodeList();
		$repo = new Repo();

		foreach($episodeList as $episode) {
			$itemNode = $itemTemplate->cloneNode(true);

			$itemNode->querySelector("title")->textContent = $episode->getTitleWithNumber();
			$itemNode->querySelector("link")->textContent = $episode->getUrl();
			$itemNode->querySelector("guid")->textContent = $episode->getMp3Url();
			$itemNode->querySelector("pubDate")->textContent = $episode->getPubDateString();
			$itemNode->querySelector("enclosure")->setAttribute("url", $episode->getMp3Url());
			$itemNode->querySelector("enclosure")->setAttribute("length", $repo->getByteLength($episode->getMp3Url()));

// TODO: I ran out of time to finish this... we need to output the REAL length in bytes (see Repo.php)
// and the episode's descriptions need setting here too.

			$channelNode->appendChild($itemNode);
		}

		echo $document->saveXML();
		die();
	}
}
