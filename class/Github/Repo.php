<?php
namespace OB1\Github;

use Gt\WebEngine\FileSystem\Path;

class Repo {
	const GITHUB_MASTER_URL = "https://api.github.com/repos/offby1podcast/archive/git/trees/master?recursive=1&test=1";
	const GITHUB_URL_BLOB = "https://api.github.com/repos/offby1podcast/archive/git/blobs";
	const CACHE_EXPIRY_SECONDS = 60 * 60; // 1 hour expiry.
	private string $cacheDir;

	public function __construct() {
		$this->cacheDir = Path::getDataDirectory() . "/cache";

		if(!is_dir($this->cacheDir)) {
			mkdir($this->cacheDir, 0775, true);
		}
	}

	public function getTree():array {
		$cacheFile = $this->cacheDir . "/master.json";

		if(!$this->cacheFileValid($cacheFile)) {
			$this->httpCache(self::GITHUB_MASTER_URL, $cacheFile);
		}

		$obj = json_decode(file_get_contents($cacheFile));
		return $obj->tree;
	}

	public function getBlobContent(string $sha):string {
		$cacheFile = $this->cacheDir . "/$sha.json";

		if(!$this->cacheFileValid($cacheFile)) {
			$url = self::GITHUB_URL_BLOB . "/$sha";
			$this->httpCache($url, $cacheFile);
		}

		$obj = json_decode(file_get_contents($cacheFile));
		return base64_decode($obj->content);
	}

	public function getShaForEpisode(int $episodeNumber):string {
		$tree = $this->getTree();
		$episodeString = str_pad($episodeNumber, 3, "0", STR_PAD_LEFT);

		foreach($tree as $item) {
			if(!str_starts_with($item->path, $episodeString)) {
				continue;
			}

			$extension = pathinfo($item->path, PATHINFO_EXTENSION);
			if($extension !== "md") {
				continue;
			}

			return $item->sha;
		}
	}

	private function cacheFileValid(string $cacheFile):bool {
		if(!file_exists($cacheFile)) {
			return false;
		}

		if(filemtime($cacheFile) < time() - self::CACHE_EXPIRY_SECONDS) {
			return false;
		}

		return true;
	}

	private function httpCache(string $url, string $cacheFile):void {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_USERAGENT, "www.offBy1podcast.com episode iterator");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		file_put_contents($cacheFile, curl_exec($ch));
	}
}
