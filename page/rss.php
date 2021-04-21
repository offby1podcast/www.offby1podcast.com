<?php
namespace OB1\Page;

use Gt\Dom\XMLDocument;
use Gt\WebEngine\Logic\Page;
use OB1\Archive\EpisodeList;
use OB1\Archive\Mp3File;
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

		foreach($episodeList as $episode) {
			$itemNode = $itemTemplate->cloneNode(true);

			$itemNode->querySelector("title")->textContent = $episode->getTitleWithNumber();
			$itemNode->querySelector("link")->textContent = $episode->getUrl();
			$itemNode->querySelector("guid")->textContent = $episode->getMp3Url();
			$itemNode->querySelector("pubDate")->textContent = $episode->getPubDateString();
			$itemNode->querySelector("enclosure")->setAttribute("url", $episode->getMp3Url());
			$mp3File = new Mp3File($episode->getNumber());
			$itemNode->querySelector("enclosure")->setAttribute("length", $mp3File->getByteLength());

			$itemNode->getElementsByTagName("subtitle")->item(0)->textContent = $episode->getIntro();
			$itemNode->getElementsByTagName("duration")->item(0)->textContent = $mp3File->getDuration();
			$itemNode->querySelector("description")->textContent = $episode->getIntro();
			$itemNode->getElementsByTagName("encoded")->item(0)->textContent = $episode->getShowNotes(true);
			$itemNode->getElementsByTagName("summary")->item(0)->textContent = $episode->getShowNotes(true);
			$itemNode->getElementsByTagName("chapters")->item(0)->setAttribute("url", "https://www.offby1podcast.com/chapters/" . $episode->getPaddedNumber());
			$channelNode->appendChild($itemNode);
		}

		echo $document->saveXML();
		exit();
	}
}
